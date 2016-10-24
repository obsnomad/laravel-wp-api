<?php namespace Threesquared\LaravelWpApi;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class WpApi
{

    /**
     * Guzzle client
     * @var Client
     */
    protected $client;

    /**
     * WP-WPI endpoint URL
     * @var string
     */
    protected $endpoint;

    /**
     * Auth headers
     * @var string
     */
    protected $auth;

    /**
     * Amount of items per page
     * @var string
     */
    protected $per_page;

    /**
     * Constructor
     *
     * @param string $endpoint
     * @param Client $client
     * @param string $auth
     */
    public function __construct($endpoint, Client $client, $auth = null, $per_page = 10)
    {
        $this->endpoint = $endpoint;
        $this->client   = $client;
        $this->auth     = $auth;
        $this->per_page = $per_page;
    }

    /**
     * Get all posts
     *
     * @param  int $page
     * @param  int $per_page
     * @return array
     */
    public function posts($page = null, $per_page = null)
    {
        return $this->get('posts', ['page' => $page, 'per_page' => $per_page ?: $this->per_page]);
    }

    /**
     * Get all pages
     *
     * @param  int $page
     * @param  int $per_page
     * @return array
     */
    public function pages($page = null, $per_page = null)
    {
        return $this->get('posts', ['type' => 'page', 'page' => $page, 'per_page' => $per_page ?: $this->per_page]);
    }

    /**
     * Get post by slug
     *
     * @param  string $slug
     * @return array
     */
    public function post($slug)
    {
        return $this->get('posts', ['filter' => ['name' => $slug]]);
    }

    /**
     * Get page by slug
     *
     * @param  string $slug
     * @return array
     */
    public function page($slug)
    {
        return $this->get('posts', ['type' => 'page', 'filter' => ['name' => $slug]]);
    }

    /**
     * Get all categories
     *
     * @return array
     */
    public function categories()
    {
        return $this->get('taxonomies/category/terms');
    }

    /**
     * Get all tags
     *
     * @return array
     */
    public function tags()
    {
        return $this->get('taxonomies/post_tag/terms');
    }

    /**
     * Get posts from category
     *
     * @param  string $slug
     * @param  int $page
     * @param  int $per_page
     * @return array
     */
    public function categoryPosts($slug, $page = null, $per_page = null)
    {
        return $this->get('posts', ['page' => $page, 'filter' => ['category_name' => $slug], 'per_page' => $per_page ?: $this->per_page]);
    }

    /**
     * Get posts by author
     *
     * @param  string $name
     * @param  int $page
     * @param  int $per_page
     * @return array
     */
    public function authorPosts($name, $page = null, $per_page = null)
    {
        return $this->get('posts', ['page' => $page, 'filter' => ['author_name' => $name], 'per_page' => $per_page ?: $this->per_page]);
    }

    /**
     * Get posts tagged with tag
     *
     * @param  string $tags
     * @param  int $page
     * @param  int $per_page
     * @return array
     */
    public function tagPosts($tags, $page = null, $per_page = null)
    {
        return $this->get('posts', ['page' => $page, 'filter' => ['tag' => $tags], 'per_page' => $per_page ?: $this->per_page]);
    }

    /**
     * Search posts
     *
     * @param  string $query
     * @param  int $page
     * @param  int $per_page
     * @return array
     */
    public function search($query, $page = null, $per_page = null)
    {
        return $this->get('posts', ['page' => $page, 'filter' => ['s' => $query], 'per_page' => $per_page ?: $this->per_page]);
    }

    /**
     * Get posts by date
     *
     * @param  int $year
     * @param  int $month
     * @param  int $page
     * @param  int $per_page
     * @return array
     */
    public function archive($year, $month, $page = null, $per_page = null)
    {
        return $this->get('posts', ['page' => $page, 'filter' => ['year' => $year, 'monthnum' => $month], 'per_page' => $per_page ?: $this->per_page]);
    }

    /**
     * Get data from the API
     *
     * @param  string $method
     * @param  array  $query
     * @return array
     */
    public function get($method, array $query = array())
    {

        try {

            $query = ['query' => $query];

            if ($this->auth) {
                $query['auth'] = $this->auth;
            }

            $response = $this->client->get($this->endpoint . $method, $query);

            $return = [
                'results' => json_decode((string) $response->getBody(), true),
                'total'   => $response->getHeaderLine('X-WP-Total'),
                'pages'   => $response->getHeaderLine('X-WP-TotalPages')
            ];

        } catch (RequestException $e) {

            $error['message'] = $e->getMessage();

            if ($e->getResponse()) {
                $error['code'] = $e->getResponse()->getStatusCode();
            }

            $return = [
                'error'   => $error,
                'results' => [],
                'total'   => 0,
                'pages'   => 0
            ];

        }

        return $return;

    }
}
