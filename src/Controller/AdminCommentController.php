<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\AdminCommentType;
use App\Repository\CommentRepository;
use App\Service\PaginationService;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Tests\Node\Obj;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminCommentController extends AbstractController
{
    /**
     * @Route("/admin/comments/{page<\d+>?1}", name="admin_comment_index")
     */
    public function index(CommentRepository $repo, $page, PaginationService $pagination)
    {
        $pagination->setEntityClass(Comment::class)
                    ->setLimit(5)
                    ->setPage($page);

        return $this->render('admin/comment/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * Permet d'éditer un commentaire
     *
     * @Route("/admin/comments/{id}/edit", name="admin_comment_edit")
     */
    public function edit(Comment $comment, ObjectManager $manager, Request $request)
    {
        $form = $this->createForm(AdminCommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($comment);
            $manager->flush();

            $this->addFlash('success', "Le commentaire n°{$comment->getId()} a bien été modifié !");
        }

        return $this->render( 'admin/comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer un commentaire
     *
     * @Route("/admin/comments/{id}/delete", name="admin_comment_delete")
     */
    public function delete(Comment $comment, ObjectManager $manager)
    {
        $id = $comment->getId();

        $manager->remove($comment);
        $manager->flush();

        $this->addFlash('success', "Le commentaire n°{$id} de {$comment->getAuthor()->getFullName()} a bien été supprimé !");

        return $this->redirectToRoute('admin_comment_index');
    }
}
