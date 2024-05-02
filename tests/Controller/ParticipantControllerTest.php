<?php

namespace App\Test\Controller;

use App\Entity\Participant;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ParticipantControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/participant/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Participant::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Participant index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'participant[pseudo]' => 'Testing',
            'participant[nom]' => 'Testing',
            'participant[prenom]' => 'Testing',
            'participant[telephone]' => 'Testing',
            'participant[mail]' => 'Testing',
            'participant[motDePasse]' => 'Testing',
            'participant[administrateur]' => 'Testing',
            'participant[actif]' => 'Testing',
            'participant[roles]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Participant();
        $fixture->setPseudo('My Title');
        $fixture->setNom('My Title');
        $fixture->setPrenom('My Title');
        $fixture->setTelephone('My Title');
        $fixture->setMail('My Title');
        $fixture->setMotDePasse('My Title');
        $fixture->setAdministrateur('My Title');
        $fixture->setActif('My Title');
        $fixture->setRoles('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Participant');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Participant();
        $fixture->setPseudo('Value');
        $fixture->setNom('Value');
        $fixture->setPrenom('Value');
        $fixture->setTelephone('Value');
        $fixture->setMail('Value');
        $fixture->setMotDePasse('Value');
        $fixture->setAdministrateur('Value');
        $fixture->setActif('Value');
        $fixture->setRoles('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'participant[pseudo]' => 'Something New',
            'participant[nom]' => 'Something New',
            'participant[prenom]' => 'Something New',
            'participant[telephone]' => 'Something New',
            'participant[mail]' => 'Something New',
            'participant[motDePasse]' => 'Something New',
            'participant[administrateur]' => 'Something New',
            'participant[actif]' => 'Something New',
            'participant[roles]' => 'Something New',
        ]);

        self::assertResponseRedirects('/participant/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getPseudo());
        self::assertSame('Something New', $fixture[0]->getNom());
        self::assertSame('Something New', $fixture[0]->getPrenom());
        self::assertSame('Something New', $fixture[0]->getTelephone());
        self::assertSame('Something New', $fixture[0]->getMail());
        self::assertSame('Something New', $fixture[0]->getMotDePasse());
        self::assertSame('Something New', $fixture[0]->getAdministrateur());
        self::assertSame('Something New', $fixture[0]->getActif());
        self::assertSame('Something New', $fixture[0]->getRoles());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Participant();
        $fixture->setPseudo('Value');
        $fixture->setNom('Value');
        $fixture->setPrenom('Value');
        $fixture->setTelephone('Value');
        $fixture->setMail('Value');
        $fixture->setMotDePasse('Value');
        $fixture->setAdministrateur('Value');
        $fixture->setActif('Value');
        $fixture->setRoles('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/participant/');
        self::assertSame(0, $this->repository->count([]));
    }
}
