<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Master;
use App\Entity\Post;
use App\Entity\Service;
use App\Entity\ServiceCategory;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\AbstractUnicodeString;
use Symfony\Component\String\Slugger\SluggerInterface;
use function Symfony\Component\String\u;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private SluggerInterface            $slugger
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        $this->loadUsers($manager);
        $this->loadTags($manager);
        $this->loadPosts($manager);
        $this->loadCategories($manager);
        $this->loadMasters($manager);
        $this->loadServices($manager);
    }

    private function loadCategories(ObjectManager $manager): void
    {
        foreach ($this->getCategoriesData() as $name) {
            $category = new ServiceCategory();
            $category->setName($name);
            $manager->persist($category);
        }
        $manager->flush();
    }

    private function getCategoriesData(): array
    {
        return [
            'Маникюр',
            'Брови',
            'Ресницы',
        ];
    }

    private function loadMasters(ObjectManager $manager): void
    {
        foreach ($this->getMastersData() as $name) {
            $master = new Master();
            $master->setName($name);
            $manager->persist($master);
        }
        $manager->flush();
    }

    private function getMastersData(): array
    {
        return [
            'Марина',
            'Ирина',
            'Мария',
            'Екатерина',
            'Ангелина',
            'Вероника',
            'Надежда',
        ];
    }

    private function loadServices(ObjectManager $manager): void
    {
        foreach ($this->getServicesData() as [$name, $price, $description]) {
            $service = new Service();
            $service->setName($name);
            $service->setPrice($price);
            $service->setDescription($description);
            $manager->persist($service);
        }
        $manager->flush();
    }

    private function getServicesData(): array
    {
        return [
            [
                'татуаж ресниц', 30,
                'Перманентный макияж век - это стандартный способ введения пигмента в верхние слои кожи. Он плотно вводится в виде стрелки черного цвета. Черный цвет пигмента считается традиционным, так как он лучше заметен и дольше держится эффект.'
            ],
            [
                'покраска ресниц', 12,
                'Процедуру окрашивания можно проходить девушкам, не страдающим аллергическими реакциями на краску, а также не имеющим заболеваний глаз или век. Процедура окрашивания ресниц стойкой краской довольно простая и длиться всего примерно 15-20 минут.'
            ],
            [
                'коррекция ресниц', 10,
                'Под коррекцией понимается восстановление изначального внешнего вида. На место старых волосков прикрепляют новые. Подобная процедура дает возможность долго носить ненатуральные реснички.'
            ],
            [
                'ламинация ресниц', 25,
                'Метод заключается в накладывании и выдерживании на волосках в течение определенного времени специального состава. Он окрашивает, создает эффект наращивания и облегчает укладку, придающую бровям и ресницам необходимый изгиб.'
            ],
            [
                'наращивание ресниц', 40,
                'Наращивание ресниц — процесс удлинения и увеличения объема натуральных ресниц при помощи искусственных ресниц. Различают два вида наращивания ресниц: поресничное наращивание и пучковое наращивание.'
            ],
            [
                'маникюр гель-лаком', 35,
                'Гель-лак – это гибрид обычного лака и геля для наращивания, поэтому он и имеет такое название. Материал собрал в себе лучшие качества: цвет и стойкость до 2-3 недель.'
            ],
            [
                'педикюр', 45,
                'Педикюр — специальный уход за пальцами ног или покрытие ногтей лаком. По сути, он представляет собой аналог маникюра для ног.'
            ],
            [
                'маникюр', 15,
                'Маникюр — косметическая процедура по обработке ногтей на пальцах рук и самих кистей рук, а то и всей руки.'
            ],
            [
                'наращивание ногтей', 55,
                'Сначала выполняется маникюр, затем наращивание, то есть удлинение ногтевого ложа, после чего на искусственные ногти наносится гель-лаковое покрытие.'
            ],
            [
                'татуаж бровей', 30,
                'Татуаж бровей представляет собой внесение пигмента в верхний слой кожи. Краситель задерживается в эпидермисе на долгое время, придавая бровям нужные очертания.'
            ],
            [
                'покраска бровей', 20,
                'Окрашивание бровей краской – это процесс придания волоскам нужного оттенка с использованием специального красителя на основе натуральных компонентов.'
            ],
            [
                'коррекция бровей', 10,
                'Коррекция бровей – это процедура, которая подразумевает придание бровям нужной формы путем удаления нежелательных волосков и дальнейшего окрашивания красителями или органической хной.'
            ],
            [
                'ламинация бровей', 25,
                'Ламинирование бровей – это косметологическая процедура, которая помогает упорядочить обширные волосы, скорректировать их форму, закрепить волосы.'
            ],
            [
                'наращивание бровей', 40,
                'Наращивание бровей – процедура, направленная на создание их идеальной формы путем точечного крепления гипоаллергенных синтетических волосков в местах, где бровь перестала расти.'
            ],
        ];
    }

    private function loadUsers(ObjectManager $manager): void
    {
        foreach ($this->getUserData() as [$fullname, $username, $password, $email, $roles]) {
            $user = new User();
            $user->setFullName($fullname);
            $user->setUsername($username);
            $user->setPassword($this->passwordHasher->hashPassword($user, $password));
            $user->setEmail($email);
            $user->setRoles($roles);

            $manager->persist($user);
            $this->addReference($username, $user);
        }

        $manager->flush();
    }

    private function loadTags(ObjectManager $manager): void
    {
        foreach ($this->getTagData() as $name) {
            $tag = new Tag($name);

            $manager->persist($tag);
            $this->addReference('tag-'.$name, $tag);
        }

        $manager->flush();
    }

    private function loadPosts(ObjectManager $manager): void
    {
        foreach ($this->getPostData() as [$title, $slug, $summary, $content, $publishedAt, $author, $tags]) {
            $post = new Post();
            $post->setTitle($title);
            $post->setSlug($slug);
            $post->setSummary($summary);
            $post->setContent($content);
            $post->setPublishedAt($publishedAt);
            $post->setAuthor($author);
            $post->addTag(...$tags);

            foreach (range(1, 5) as $i) {
                /** @var User $commentAuthor */
                $commentAuthor = $this->getReference('john_user');

                $comment = new Comment();
                $comment->setAuthor($commentAuthor);
                $comment->setContent($this->getRandomText(random_int(255, 512)));
                $comment->setPublishedAt(new \DateTime('now + '.$i.'seconds'));

                $post->addComment($comment);
            }

            $manager->persist($post);
        }

        $manager->flush();
    }

    /**
     * @return array<array{string, string, string, string, array<string>}>
     */
    private function getUserData(): array
    {
        return [
            // $userData = [$fullname, $username, $password, $email, $roles];
            ['Jane Doe', 'jane_admin', 'kitten', 'jane_admin@symfony.com', ['ROLE_ADMIN']],
            ['Tom Doe', 'tom_admin', 'kitten', 'tom_admin@symfony.com', ['ROLE_ADMIN']],
            ['John Doe', 'john_user', 'kitten', 'john_user@symfony.com', ['ROLE_USER']],
        ];
    }

    /**
     * @return string[]
     */
    private function getTagData(): array
    {
        return [
            'lorem',
            'ipsum',
            'consectetur',
            'adipiscing',
            'incididunt',
            'labore',
            'voluptate',
            'dolore',
            'pariatur',
        ];
    }

    /**
     * @throws \Exception
     *
     * @return array<int, array{0: string, 1: AbstractUnicodeString, 2: string, 3: string, 4: \DateTime, 5: User, 6: array<Tag>}>
     */
    private function getPostData(): array
    {
        $posts = [];
        foreach ($this->getPhrases() as $i => $title) {
            // $postData = [$title, $slug, $summary, $content, $publishedAt, $author, $tags, $comments];

            /** @var User $user */
            $user = $this->getReference(['jane_admin', 'tom_admin'][0 === $i ? 0 : random_int(0, 1)]);

            $posts[] = [
                $title,
                $this->slugger->slug($title)->lower(),
                $this->getRandomText(),
                $this->getPostContent(),
                (new \DateTime('now - '.$i.'days'))->setTime(random_int(8, 17), random_int(7, 49), random_int(0, 59)),
                // Ensure that the first post is written by Jane Doe to simplify tests
                $user,
                $this->getRandomTags(),
            ];
        }

        return $posts;
    }

    /**
     * @return string[]
     */
    private function getPhrases(): array
    {
        return [
            'Lorem ipsum dolor sit amet consectetur adipiscing elit',
            'Pellentesque vitae velit ex',
            'Mauris dapibus risus quis suscipit vulputate',
            'Eros diam egestas libero eu vulputate risus',
            'In hac habitasse platea dictumst',
            'Morbi tempus commodo mattis',
            'Ut suscipit posuere justo at vulputate',
            'Ut eleifend mauris et risus ultrices egestas',
            'Aliquam sodales odio id eleifend tristique',
            'Urna nisl sollicitudin id varius orci quam id turpis',
            'Nulla porta lobortis ligula vel egestas',
            'Curabitur aliquam euismod dolor non ornare',
            'Sed varius a risus eget aliquam',
            'Nunc viverra elit ac laoreet suscipit',
            'Pellentesque et sapien pulvinar consectetur',
            'Ubi est barbatus nix',
            'Abnobas sunt hilotaes de placidus vita',
            'Ubi est audax amicitia',
            'Eposs sunt solems de superbus fortis',
            'Vae humani generis',
            'Diatrias tolerare tanquam noster caesium',
            'Teres talis saepe tractare de camerarius flavum sensorem',
            'Silva de secundus galatae demitto quadra',
            'Sunt accentores vitare salvus flavum parses',
            'Potus sensim ad ferox abnoba',
            'Sunt seculaes transferre talis camerarius fluctuies',
            'Era brevis ratione est',
            'Sunt torquises imitari velox mirabilis medicinaes',
            'Mineralis persuadere omnes finises desiderium',
            'Bassus fatalis classiss virtualiter transferre de flavum',
        ];
    }

    private function getRandomText(int $maxLength = 255): string
    {
        $phrases = $this->getPhrases();
        shuffle($phrases);

        do {
            $text = u('. ')->join($phrases)->append('.');
            array_pop($phrases);
        } while ($text->length() > $maxLength);

        return $text;
    }

    private function getPostContent(): string
    {
        return <<<'MARKDOWN'
            Lorem ipsum dolor sit amet consectetur adipisicing elit, sed do eiusmod tempor
            incididunt ut labore et **dolore magna aliqua**: Duis aute irure dolor in
            reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
            Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia
            deserunt mollit anim id est laborum.

              * Ut enim ad minim veniam
              * Quis nostrud exercitation *ullamco laboris*
              * Nisi ut aliquip ex ea commodo consequat

            Praesent id fermentum lorem. Ut est lorem, fringilla at accumsan nec, euismod at
            nunc. Aenean mattis sollicitudin mattis. Nullam pulvinar vestibulum bibendum.
            Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos
            himenaeos. Fusce nulla purus, gravida ac interdum ut, blandit eget ex. Duis a
            luctus dolor.

            Integer auctor massa maximus nulla scelerisque accumsan. *Aliquam ac malesuada*
            ex. Pellentesque tortor magna, vulputate eu vulputate ut, venenatis ac lectus.
            Praesent ut lacinia sem. Mauris a lectus eget felis mollis feugiat. Quisque
            efficitur, mi ut semper pulvinar, urna urna blandit massa, eget tincidunt augue
            nulla vitae est.

            Ut posuere aliquet tincidunt. Aliquam erat volutpat. **Class aptent taciti**
            sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Morbi
            arcu orci, gravida eget aliquam eu, suscipit et ante. Morbi vulputate metus vel
            ipsum finibus, ut dapibus massa feugiat. Vestibulum vel lobortis libero. Sed
            tincidunt tellus et viverra scelerisque. Pellentesque tincidunt cursus felis.
            Sed in egestas erat.

            Aliquam pulvinar interdum massa, vel ullamcorper ante consectetur eu. Vestibulum
            lacinia ac enim vel placerat. Integer pulvinar magna nec dui malesuada, nec
            congue nisl dictum. Donec mollis nisl tortor, at congue erat consequat a. Nam
            tempus elit porta, blandit elit vel, viverra lorem. Sed sit amet tellus
            tincidunt, faucibus nisl in, aliquet libero.
            MARKDOWN;
    }

    /**
     * @throws \Exception
     *
     * @return array<Tag>
     */
    private function getRandomTags(): array
    {
        $tagNames = $this->getTagData();
        shuffle($tagNames);
        $selectedTags = \array_slice($tagNames, 0, random_int(2, 4));

        return array_map(function ($tagName) {
            /** @var Tag $tag */
            $tag = $this->getReference('tag-'.$tagName);

            return $tag;
        }, $selectedTags);
    }
}
