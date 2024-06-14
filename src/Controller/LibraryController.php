<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LibraryController extends AbstractController
{
    #[Route('/library', name: 'library')]
    public function index(): Response
    {
        return $this->render('library/index.html.twig', [
            'controller_name' => 'LibraryController',
        ]);
    }

    #[Route("/library/add", name: "library_add")]
    public function addRoute(): Response
    {
        return $this->render('library/add.html.twig');
    }

    #[Route('/library/add/book', name: 'add_book')]
    public function addBook(
        ManagerRegistry $doctrine,
        Request $request
    ): Response {
        if ($request->isMethod('POST')) {
            // Hämta från formulär
            $title = (string)$request->request->get('title');
            $isbn = (int)$request->request->get('isbn');
            $author = (string)$request->request->get('author');
            $image = (string)$request->request->get('image');

            // Skapa ny bok
            $book = new Book();
            $book->setTitle($title);
            $book->setIsbn($isbn);
            $book->setAuthor($author);
            $book->setImage($image);

            $entityManager = $doctrine->getManager();

            // tell Doctrine you want to (eventually) save the book
            // (no queries yet)
            $entityManager->persist($book);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();

            return $this->redirectToRoute('library_view_all');
        }

        return $this->redirectToRoute('library_add');
    }

    #[Route('/library/view', name: 'library_view_all')]
    public function viewAll(
        BookRepository $bookRepository
    ): Response {
        // hämta alla böcker
        $books = $bookRepository->findAll();

        $data = [
            'books' => $books
        ];

        return $this->render('library/view_all.html.twig', $data);
    }

    #[Route('/library/view/{id}', name: 'library_view_id')]
    public function viewById(
        BookRepository $bookRepository,
        int $id
    ): Response {
        // hämta bok för inskickat id
        $book = $bookRepository->find($id);

        // finne inte boken för id lyft exception
        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for id '.$id
            );
        }

        $data = [
            'book' => $book
        ];

        return $this->render('library/view_id.html.twig', $data);
    }

    #[Route('/library/edit/{id}', name: 'library_edit')]
    public function editRoute(
        BookRepository $bookRepository,
        int $id
    ): Response {
        // hämta bok för inskickat id
        $book = $bookRepository->find($id);

        // finns inte boken för id lyft exception
        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for id '.$id
            );
        }

        $data = [
            'book' => $book
        ];

        return $this->render('library/edit.html.twig', $data);
    }

    #[Route('/library/edit/{id}/update', name: 'edit_book')]
    public function editBook(
        ManagerRegistry $doctrine,
        Request $request,
        int $id
    ): Response {
        $entityManager = $doctrine->getManager();

        // hämta bok för inskickat id
        $book = $entityManager->getRepository(Book::class)->find($id);

        // finns inte boken för id lyft exception
        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for id ' . $id
            );
        }

        if ($request->isMethod('POST')) {
            // Hämta från formulär
            $title = (string)$request->request->get('title');
            $isbn = (int)$request->request->get('isbn');
            $author = (string)$request->request->get('author');
            $image = (string)$request->request->get('image');

            // Uppdatera värden
            $book->setTitle($title);
            $book->setIsbn($isbn);
            $book->setAuthor($author);
            $book->setImage($image);

            // exekvera
            $entityManager->flush();

            return $this->redirectToRoute('library_view_all');
        }

        return $this->redirectToRoute('library_edit');
    }

    #[Route('/library/delete/{id}', name: 'delete_book')]
    public function deleteProductById(
        ManagerRegistry $doctrine,
        int $id
    ): Response {
        $entityManager = $doctrine->getManager();

        // hämta bok för inskickat id
        $book = $entityManager->getRepository(Book::class)->find($id);

        // finns inte boken för id lyft exception
        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for id '.$id
            );
        }

        // radera bok
        $entityManager->remove($book);

        // exekvera
        $entityManager->flush();

        return $this->redirectToRoute('library_view_all');
    }
}
