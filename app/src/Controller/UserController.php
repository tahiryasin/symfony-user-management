<?php

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class UserController
 * @package App\Controller
 */
class UserController extends AbstractController
{
    /**
     * @var Connection
     */
    private Connection $connection;
	
	/**
     * @var ValidatorInterface
     */
	private ValidatorInterface $validator;
	
    /**
     * UserController constructor.
     *
     * @param Connection $connection
	 * @param ValidatorInterface $validator
     */
    public function __construct(Connection $connection, ValidatorInterface $validator)
	{
		$this->connection = $connection;
		$this->validator = $validator;
	}

    /**
     * Display the list of users.
     *
     * @Route("/user", name="user_list", methods={"GET"})
     *
     * @param Request $request
     * @return Response
     */
	#[Route('/user', name: 'user_list', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $users = $this->executeQuery("SELECT * FROM user", []);

        return $this->render('user.html.twig', [
            'obj' => 'GET',
            'users' => $users
        ]);
    }
	
	/**
     * Delete a user.
     *
     * @Route("/user/delete", name="user_delete", methods={"POST"})
     *
     * @param Request $request
     * @return Response
     * @throws AccessDeniedException if CSRF token is invalid
     */
	#[Route('/user/delete', name: 'user_delete', methods: ['POST'])]
    public function delete(Request $request): Response
    {
        $id = $request->request->getInt('id');
        $submittedToken = $request->request->get('token');

        if (!$this->isCsrfTokenValid('delete-user', $submittedToken)) {
            throw new AccessDeniedException('Invalid CSRF token.');
        }

        if ($id) {
            $this->executeQuery("DELETE FROM user WHERE id = :id", ['id' => $id]);
            $this->addFlash('success', 'User deleted successfully.');
        }

        return $this->redirectToRoute('user_list');
    }

	/**
     * Create a new user.
     *
     * @Route("/user", name="user_create", methods={"POST"})
     *
     * @param Request $request
     * @return Response
     * @throws AccessDeniedException if CSRF token is invalid
     */
	#[Route('/user', name: 'user_create', methods: ['POST'])]
    public function save(Request $request): Response
    {
        $submittedToken = $request->request->get('token');

        if (!$this->isCsrfTokenValid('add-user', $submittedToken)) {
            throw new AccessDeniedException('No token given or token is wrong.');
        }

        $data = [
            'firstname' => $request->request->get('firstname'),
            'lastname' => $request->request->get('lastname'),
            'address' => $request->request->get('address')
        ];

        $constraints = new Assert\Collection([
            'firstname' => [new Assert\NotBlank(), new Assert\Length(['max' => 50])],
            'lastname' => [new Assert\NotBlank(), new Assert\Length(['max' => 50])],
            'address' => [new Assert\NotBlank(), new Assert\Length(['max' => 155])]
        ]);

        $violations = $this->validator->validate($data, $constraints);

        if (count($violations) > 0) {
            $this->addFlash('error', 'Form validation failed.');

			$users = $this->executeQuery("SELECT * FROM user", []);
            return $this->render('user.html.twig', [
                'data' => $data,
				'users'=> $users
            ]);
        }

        $dataString = sprintf('%s - %s - %s', $data['firstname'], $data['lastname'], $data['address']);
        $this->executeQuery("INSERT INTO user (data) VALUES (:data)", ['data' => $dataString]);
        $this->addFlash('success', 'User created successfully.');

        return $this->redirectToRoute('user_list');
    }

	/**
     * Execute a database query.
     *
     * @param string $sql The SQL query to execute
     * @param array $params The parameters to bind to the query
     * @return array The result set
     */
    private function executeQuery(string $sql, array $params = []): array
    {
        $stmt = $this->connection->prepare($sql);
        $result = $stmt->executeQuery($params);

        return $result->fetchAllAssociative();
    }
}
