<div class="row">

    <div class="col-sm-8 blog-main">

        <div class="blog-post">

            <?php

            require_once('/../model/news.php');

            $id = $_GET['id'];

            $news = new News();
            $news->selectNews($id);

            $output = '<h2 class="blog-post-title">'.$news->title.'</h2>';
            $pubDate = date('H:i:s - d.m.Y', $news->pubDate);
            $output .= '<p class="blog-post-meta">'.$pubDate.'</p>';
            $output .= isset($news->imageURL) ? '<img src="'.$news->imageURL.'" alt="'.$news->title.'">' : '';
            $output .= $news->text;
            $output .= '<p><a href="'.$news->link.'">Cсылка на оригинальную статью</a>';

            echo $output;
            ?>

        </div><!-- /.blog-post -->

        <a href="index.php" class="button28">На главную</a>
    </div><!-- /.blog-main -->

    <div class="col-sm-3 col-sm-offset-1 blog-sidebar">
        <div class="sidebar-module">
            <h4>Рекомендуем посмотреть:</h4>
            <ol class="list-unstyled">
                <?php

                require_once('/../controllers/NewsController.php');

                $controller = new NewsController();
                $newsList = $controller->actionSelectNews(10, $news->category);

                if (count($newsList) == 1)
                    $newsList = $controller->actionSelectNews(10);

                foreach ($newsList as $news) {
                    if ($news['id'] == $id)
                        continue;
                    echo  '<hr><a href="news.php?id='.$news['id'].'">'.$news['title'].'</a></hr>';
                }
                
                ?>
            </ol>
        </div>
    </div><!-- /.blog-sidebar -->

</div><!-- /.row -->