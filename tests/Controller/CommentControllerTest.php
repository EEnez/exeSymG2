<?php

namespace App\Tests\Controller;

use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class CommentControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/comment/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Comment::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Comment index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'comment[commentMessage]' => 'Testing',
            'comment[commentDateCreated]' => 'Testing',
            'comment[commentPublished]' => 'Testing',
            'comment[post]' => 'Testing',
            'comment[user]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Comment();
        $fixture->setCommentMessage('My Title');
        $fixture->setCommentDateCreated('My Title');
        $fixture->setCommentPublished('My Title');
        $fixture->setPost('My Title');
        $fixture->setUser('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Comment');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Comment();
        $fixture->setCommentMessage('Value');
        $fixture->setCommentDateCreated('Value');
        $fixture->setCommentPublished('Value');
        $fixture->setPost('Value');
        $fixture->setUser('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'comment[commentMessage]' => 'Something New',
            'comment[commentDateCreated]' => 'Something New',
            'comment[commentPublished]' => 'Something New',
            'comment[post]' => 'Something New',
            'comment[user]' => 'Something New',
        ]);

        self::assertResponseRedirects('/comment/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getCommentMessage());
        self::assertSame('Something New', $fixture[0]->getCommentDateCreated());
        self::assertSame('Something New', $fixture[0]->getCommentPublished());
        self::assertSame('Something New', $fixture[0]->getPost());
        self::assertSame('Something New', $fixture[0]->getUser());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Comment();
        $fixture->setCommentMessage('Value');
        $fixture->setCommentDateCreated('Value');
        $fixture->setCommentPublished('Value');
        $fixture->setPost('Value');
        $fixture->setUser('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/comment/');
        self::assertSame(0, $this->repository->count([]));
    }
}
