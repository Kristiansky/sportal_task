<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class DataController extends AbstractController
{
    /**
     * @Route("/data", name="app_data")
     */
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to the data controller!',
        ]);
    }

    /**
     * @Route("/seed_posts", name="seed_posts")
     */
    public function seed_posts(){
        $data = [
            [
                'title' => 'Две големи изненади в днешните квалификации за Евро 2024',
                'content' => '<p>Днес се изиграха последните седем срещи от квалификациите за <a class="autolink-enabled" data-resource-id="23912" data-resource-type="team" data-source="football-api" href="/football/team-23912">Евро</a> 2024 за този месец, като следващите битки за място на европейските финали ще са чак през лятото. Изненадата се получи в Глазгоу, където <a class="autolink-enabled" data-resource-id="1689" data-resource-type="team" data-source="football-api" href="/football/team-1689">Шотландия</a> победи <a class="autolink-enabled" data-resource-id="1770" data-resource-type="team" data-source="football-api" href="/football/team-1770">Испания</a> с 2:0 и оглави класирането в група "А" с пълен актив от 6 точки. И двата гола за "гайдарите" отбеляза халфът на Манчестър Юнайтед Скот Мактоминей.</p>',
                'status' => 1,
                'created_at' => '2023-03-24 21:46:59',
                'published_at' => '2023-03-24 01:09:57',
            ],[
                'title' => 'Сираков: Който има разногласия с мен, си тръгва!',
                'content' => '<p>Наско Сираков отправи ултиматум към ръководителите в Левски.&nbsp;"Който има разногласия с мен, си тръгва!", обяви мажоритарният собственик на клуба.</p><p>В Бургас, Сираков откри информационна кампания по повод увеличаването на капитала на клуба. Утре обиколката му продължава в Сливен.</p><p>"Супер доволен съм. Мисля, че всички левскари, които бяха тук, също са доволни. Бъдещето на клуба е все още трудно и тежко, но има достатъчно светлинка в тунела.</p>',
                'status' => 0,
                'created_at' => '2023-03-25 21:46:59',
                'published_at' => null,
            ],[
                'title' => 'Стилиян Петров: Г-н Михайлов, чакаме да се извините публично на всички',
                'content' => '<p>Легендата на българския футбол Стилиян Петров призова президента на БФС Борислав Михайлов да се извини публично след двете поражението на националния ни отбор от Черна гора с 0:1 и Унгария с 0:3. Ето какво написа Стенли в своя „Фейсбук“ профил.</p><p>„БФС трябва да се извини публично за некомпетентното и порочно управление.</p><p>Представянето на националния отбор изисква способност да се справяме с огромно напрежение и очаквания. Цялата нация гледа как се изправяме срещу противници, които са също толкова мотивирани да представят страната си по най-добрия възможен начин.</p><p>Единственият начин да придобием тази способност е чрез опит. Никой не се ражда с него и нито един млад играч не може да играе постоянно на високо ниво без него. Играчите трябва да се въвеждат постепенно, просто да се избутат леко извън зоната на комфорт, за да растат и да стават по-силни.</p>',
                'status' => 1,
                'created_at' => '2023-03-26 21:48:41',
                'published_at' => '2023-03-27 01:09:57',
            ]
        ];
        $entityManager = $this->getDoctrine()->getManager();
        for ($i=0;$i <= 50; $i++){
            $postData = $data[rand(0,2)];

            $post = new Post();
            $post->setTitle($postData['title']);
            $post->setContent($postData['content']);
            $post->setStatus($postData['status']);
            $post->setCreatedAt(new \DateTime($postData['created_at']));
            if($postData['created_at'] != null){
                $post->setPublishedAt(new \DateTime($postData['created_at']));
            }

            $entityManager->persist($post);
            $entityManager->flush();
        }
        return $this->json('Db seeded with posts data!');
    }
}
