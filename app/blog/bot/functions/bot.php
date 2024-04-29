<?php
class bot extends Database
{
    function load_blog()
    {
        $data = $this->api_call("https://eventregistry.org/api/v1/article/getArticles?resultType=articles&keyword=Bitcoin&keyword=Ethereum&keyword=Litecoin&keywordOper=or&lang=eng&articlesSortBy=date&includeArticleConcepts=true&includeArticleCategories=true&articleBodyLen=5000&articlesCount=10&apiKey=f5144915-1624-42f5-8646-35a0cbb7b8ce");
        if (isset($data->articles->results)) {
            // var_dump($data->articles->results);
            $bots = json_decode(json_encode($data->articles->results), true);
            foreach ($bots as $bot) {
                // var_dump ($bot);
                // return;
                $post = [];
                $post['user_id'] = 1;
                $post['post_live'] = 1;
                // $post['post_desc'] = $this->short_text($bot['body'], 5000);
                $post['post_desc'] = $bot['body'];
                $post['post_color'] = "bg-dark";
                if(!isset($bot['title'])) { $bot['title'] = $this->short_text($bot['body'], 30); }
                $post['post_title'] = $bot['title'];
                $post['post_slug'] = preg_replace('/[^A-Za-z0-9 ]/', '-', str_replace(" ", "-", $bot['title']));
                $post['post_media'] = $bot['image'];
                $post['created_at'] = $bot['dateTime'];
                if ($this->getall("posts", "post_slug = ?", [$post['post_slug']], fetch: "") > 0) {
                    continue;
                }
                if (!$this->quick_insert("posts", $post, $post['post_title'] . " blog added.")) {
                    continue;
                }
                $get = $this->getall("posts", "post_slug = ?", [$post['post_slug']], fetch: "details");
                $contents = ["post_id" => $get['id'], "body" => $bot['body'], "blank" => 1];
                $this->quick_insert("contents", $contents, $post['post_title'] . " content added.");
            }
        }
    }
}
