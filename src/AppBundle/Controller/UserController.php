<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\User;
use AppBundle\Entity\UserLog;
use AppBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\FormError;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * User controller.
 *
 * @Route("/user")
 */
class UserController extends Controller
{
    /**
     * 拥有非ROLE_ADMIN角色的只能操作和他companyCode一样的人员(不包括他本身).
     * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_LOADOFFICER_MANAGER') or has_role('ROLE_EXAMER_HPL')")
     * @Route("/", name="user_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $title = '用户列表';
        $perPageLimit = $request->query->get('perPageLimit') ? $request->query->get('perPageLimit') : 20;

        $vars['name'] = $request->query->get('vars')['name'];
        $vars['mobile'] = $request->query->get('vars')['mobile'];
        $vars['username'] = $request->query->get('vars')['username'];
        $roles = null;

        if ($this->isGranted('ROLE_ADMIN')) {
            $userId = null;
            $companyCode =null;
            $vars['company'] = $request->query->get('vars')['company'];
            $companyName = $vars['company'];
        } elseif ($this->getUser()->hasRole('ROLE_LOADOFFICER_MANAGER')) {
            $userId = $this->getUser()->getId();
            $companyCode = $this->getUser()->getCompanyCode();
            $companyName = null;
        } else {
            $userId = $this->getUser()->getId();
            $companyCode = null;
            $companyName = $this->getUser()->getCompany();
            $roles = array(
                'a:1:{i:0;s:16:"ROLE_LOADOFFICER";}',
                'a:1:{i:0;s:24:"ROLE_LOADOFFICER_MANAGER";}',
                'a:2:{i:0;s:16:"ROLE_LOADOFFICER";i:1;s:24:"ROLE_LOADOFFICER_MANAGER";}',
            );
        }

        $query = $this->getDoctrine()->getRepository('AppBundle:User')
            ->findUser($userId, $companyCode, $vars['name'], $vars['mobile'], $vars['username'], $companyName, $roles);
        ;

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            $perPageLimit /*limit per page */
        );

        return $this->render('user/index.html.twig', array(
            'title' => $title,
            'vars' => $vars,
            'pagination' => $pagination,
            'perPageLimit' => $perPageLimit,
        ));
    }

    /**
     * Creates a new User entity.
     * 拥有非ROLE_ADMIN角色的只能操作和他companyCode一样的人员(不包括他本身).
     * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_LOADOFFICER_MANAGER')")
     * @Route("/new", name="user_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm('AppBundle\Form\UserType', $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing User entity.
     * 拥有非ROLE_ADMIN角色的只能操作和他companyCode一样的人员(不包括他本身).
     * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_LOADOFFICER_MANAGER')")
     * @Route("/{id}/edit", name="user_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, User $user)
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            $this->checkOwner($user);
        }

        // 编辑模式下用Custom(对密码不做验证)和CustomEdit验证组
        $validationGroups = array('Custom', 'CustomEdit');
        $editForm = $this->createForm('AppBundle\Form\UserType', $user, array("validation_groups" => $validationGroups));
        $editForm->handleRequest($request);

        // 对服务公司字段单独做验证处理（可能有更好的方法）
        if ($editForm->isSubmitted()) {
            $serviceCompanies = $editForm->get('serviceCompanies')->getViewData();
            if (!$serviceCompanies) {
                $error = new FormError('所服务公司不能为空');
                $editForm->get('serviceCompanies')->addError($error);
            }
        }

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $userManager = $this->get('fos_user.user_manager');
            $userManager->updateUser($user);

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', array(
            'user' => $user,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * @Security("has_role('ROLE_EXAMER_MANAGER')")
     * @Route("/edit_abnoramal", name="user_edit_abnormal")
     * @Method({"GET", "POST"})
     */
    public function editAbnormalAction(Request $request)
    {
        $editForm = $this->createFormBuilder()
            ->add('abnormal', EntityType::class, array(
                'class' => 'AppBundle:User',
                // 只获取有审核师角色的列表
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.roleType = :roleType and u.enabled = true')
                        ->setParameter('roleType', User::TYPE_EXAMER)
                    ;
                },
                'multiple' => true,
                'choice_label' => 'name',
                'label' => false,
                    'attr' => array(
                        'class' => 'select2',
                    ),
                'data' => $this->getDoctrine()->getRepository('AppBundle:User')->findBy(['abnormal' => 1, 'roleType' => User::TYPE_EXAMER, 'enabled' => true]),
             ))
            ->getForm()
        ;

        $em = $this->getDoctrine()->getManager();
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            //将非选中的用户更新为非异常
            $q = $em->createQuery('update AppBundle:user u set u.abnormal = 0 where u not in (:users)')
                ->setParameter('users', $editForm->getData()['abnormal']);
            $q->execute();
            //将选中的用户更新为异常
            $q = $em->createQuery('update AppBundle:user u set u.abnormal = 1 where u in (:users)')
                ->setParameter('users', $editForm->getData()['abnormal']);
            $q->execute();

            $this->addFlash(
                'notice',
                '保存成功'
            );
        }

        return $this->render('user/edit_abnormal.html.twig', array(
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a User entity.不通过form,直接删除
     * 拥有非ROLE_ADMIN角色的只能操作和他companyCode一样的人员(不包括他本身).
     * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_LOADOFFICER_MANAGER')")
     * @Route("/{id}/delete", name="user_delete")
     * 
     */
    public function deleteAction(Request $request, User $user)
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            $this->checkOwner($user);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        $this->addFlash(
            'notice',
            '删除成功'
        );

        return $this->redirectToRoute('user_index');
    }

    /**
     * 检查ROLE_LOADOFFICER_MANAGER角色是否操作的是自己范围的用户（编辑和删除时会检查）
     */
    private function checkOwner(User $user)
    {
        $companyCode = $this->getUser()->getCompanyCode();

        if ($companyCode != $user->getCompanyCode()) {
            throw $this->createAccessDeniedException('你无权限操作该用户！');
        }
    }

    /**
     * ajax更改用户上班状态
     * @Route("/edit_job", name="switch_job_status")
     */
    public function modifyJob(Request $request)
    {
        $user = $this->getUser();
        $status = $user->getIsJob();
        $user->setIsJob(!$status);

        $userID = $user->getId();
        $createAt = new \DateTime();
        $userLog = new UserLog();
        $userLog->setUserId($userID);
        $userLog->setJobStatus($status);
        $userLog->setCreatedAt($createAt);
    
        $em = $this->getDoctrine()->getManager();
                
        $em->persist($userLog);
        $em->flush();
        
        return new JsonResponse(["status"=>200,"message"=>"修改成功!","jobStatus"=>$user->getIsJob()]);
    } 
}
