<?php

namespace App\Controller;

use App\Entity\Expense;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ExpensesController extends AbstractController
{

    public function __construct(private ManagerRegistry $doctrine) {}

    #[Route('expenses/get/{id}', name: 'app_expenses_get_one', methods: 'GET')]
    public function get($id): Response {
        
        $data = $this->doctrine->getRepository(Expense::class)->find($id);
    
        return $this->json ($data);
    }

    #[Route('expenses/add', name: 'app_expenses_add', methods: 'POST')]
    public function add(Request $request): Response {
          
        $expense = new Expense();
        $data = json_decode($request->getContent(), true);
        $expense->setPrice($data['price']);
        $expense->setName($data['name']);
        $expense->setPaid($data['paid']);

        $em = $this->doctrine->getManager();
        $em->persist($expense);
        $em->flush();


        return $this->json([
            'inserted successffuly'
        ]);
    }

    #[Route('expenses/update/{id}', name: 'app_expenses_update', methods: 'PUT')]
    public function update(Request $request, $id): Response {
        
        $dataToUpdate = $this->doctrine->getRepository(Expense::class)->find($id);
        $data = json_decode($request->getContent(), true);
        $dataToUpdate->setPrice($data['price']);
        $dataToUpdate->setName($data['name']);
        $dataToUpdate->setPaid($data['paid']);

        $em = $this->doctrine->getManager();
        $em->persist($dataToUpdate);
        $em->flush();


        return $this->json([
            'change successffuly'
        ]);
    }

    #[Route('expenses/delete/{id}', name: 'app_expenses_delete', methods: 'DELETE')]
    public function delete($id): Response {
        
        $dataToDelete = $this->doctrine->getRepository(Expense::class)->find($id);
    
        $em = $this->doctrine->getManager();
        $em->remove($dataToDelete);
        $em->flush();


        return $this->json([
            'delete successfully'
        ]);
    }

    #[Route('expenses/get', name: 'app_expenses_get', methods: 'GET')]
    public function fetchAll(): Response {
        
        $allData = $this->doctrine->getRepository(Expense::class)->findAll();
        
        return $this->json ($allData);
    }
}
