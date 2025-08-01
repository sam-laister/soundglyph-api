<?php

namespace App\DataFixtures;

use App\Entity\Album;
use App\Entity\Artist;
use App\Entity\Media;
use App\Entity\Track;
use App\Entity\User;
use App\Services\BucketService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AppFixtures extends Fixture
{

    private BucketService $bucketService;

    public function __construct(BucketService $bucketService)
    {
        $this->bucketService = $bucketService;
    }

    public function load(ObjectManager $manager): void
    {
        // main user
        $user = new User();
        $user->setEmail('admin@example.com');
        $user->setPassword(password_hash('admin', PASSWORD_DEFAULT));
        $user->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);

        // media

        $artworkMedia = new Media();
        $artworkMedia->setPath($this->bucketService->uploadFile(new File(__DIR__ . '/artwork.jpg'), 'artwork.jpg'));
        $artworkMedia->setOwner($user);

        $audioMedia = new Media();
        $audioMedia->setPath($this->bucketService->uploadFile(new File(__DIR__ . '/audio.mp3'), 'audio.mp3'));
        $audioMedia->setOwner($user);

        $manager->persist($artworkMedia);
        $manager->persist($audioMedia);

        // artists
        $artists = [
            new Artist(),
            new Artist(),
        ];

        $artists[0]->setName('Artist 1');
        $artists[1]->setName('Artist 2');

        $artists[0]->setArtwork($artworkMedia);
        $artists[1]->setArtwork($artworkMedia);

        foreach ($artists as $artist) {
            $manager->persist($artist);
        }

        // albums and tracks

        $tracks = [];

        for ($i = 0; $i < 10; $i++) {
            $track = new Track();
            $track->setTitle('Track ' . $i);

            // One to one needs unique
            $audioMedia = new Media();
            $audioMedia->setPath($this->bucketService->uploadFile(new File(__DIR__ . '/audio.mp3'), 'audio.mp3'));
            $audioMedia->setOwner($user);
            $manager->persist($audioMedia);

            $track->setArtwork($audioMedia);
            $track->setAudio($audioMedia);
            $track->setDuration(100);
            $manager->persist($track);

            $tracks[] = $track;
        }

        $albums = [
            new Album(),
            new Album(),
        ];

        $albums[0]->setTitle('Album 1');
        $albums[1]->setTitle('Album 2');

        $albums[0]->setArtwork($artworkMedia);
        $albums[1]->setArtwork($artworkMedia);

        $albums[0]->addArtist($artists[0]);
        $albums[1]->addArtist($artists[1]);

        for ($i = 0; $i < 10; $i++) {
            if ($i % 2 === 0) {
                $albums[0]->addTrack($tracks[$i]);
            } else {
                $albums[1]->addTrack($tracks[$i]);
            }
        }

        foreach ($albums as $album) {
            $manager->persist($album);
        }

        $manager->flush();
    }
}
