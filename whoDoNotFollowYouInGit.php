<?php

declare(strict_types=1);

include file_exists("listOfAllowedAccounts.php") ? 'listOfAllowedAccounts.php' : 'listOfAllowedAccounts-sample.php';

class Git
{
    protected array $followers = [];
    protected array $following = [];
    protected string $followingUrl;
    protected string $followersUrl;

    public function __construct(
        protected string $userName
        )
    {
        $this->followersUrl = "https://api.github.com/users/$this->userName/followers";
        $this->followingUrl = "https://api.github.com/users/$this->userName/following";
    }

    public function simpleCurl(string $url)
    {
        $requestCurl = curl_init($url);

        curl_setopt($requestCurl, CURLOPT_HTTPHEADER, [
            'User-Agent: Awesome-Octocat-App'
        ]);
        curl_setopt($requestCurl, CURLOPT_RETURNTRANSFER, true);
        $code = curl_getinfo($requestCurl, CURLINFO_HTTP_CODE);
        $responseCurl = curl_exec($requestCurl);
        
        if($this->isHttpSuccess($code) == false)
        {
            throw new \Exception($responseCurl,$code);
        }

        if(curl_error($requestCurl)){
            var_dump('Error:');
            throw new \Exception(curl_error($requestCurl));
        }

        return $responseCurl;
    }

    public function isHttpSuccess(string|int $status) {
        return 2 == (int)floor($status / 100);
    }

    public function getFollowers(): ?array
    {
        $followersResponse = $this->simpleCurl($this->followersUrl);

        return json_decode($followersResponse, true);
    }

    public function getFollowing(): ?array
    {
        $followingResponse = $this->simpleCurl($this->followingUrl);

        return json_decode($followingResponse, true);
    }

    public function followingMinusFollowers(): array
    {
        $this->followers = $this->getFollowers();
        $this->following = $this->getFollowing();

        return array_map(
            'unserialize',
            array_diff(
                array_map(
                    'serialize',
                    $this->following
                ),
                array_map(
                    'serialize',
                    $this->followers
                )
            )
        );
    }

    public function getAllowedLoginAccounts(): array
    {
        return ALLOWED_ACCOUNTS ?? [];
    }

    public function whoDoNotFollowYou()
    {
        $followingMinusFollowers = $this->followingMinusFollowers();
        $allowed = $this->getAllowedLoginAccounts();
        return array_filter(
            $followingMinusFollowers,
            fn($x) => !in_array($x['login'] ?? null, $allowed) ? $x : null
        );
    }

    public function whoDoNotFollowYouByKey($key = 'login')
    {
        $whoDoNotFollowYou = $this->whoDoNotFollowYou();

        return array_map(
            fn($x) => $x[$key],
            $whoDoNotFollowYou
        );
    }

    public function showFirstMessage()
    {
        echo "### YUOR USERNAME: $this->userName".PHP_EOL;
        echo '### THE ACCOUNT(S) THAT YOU FOLLOWED BUT THEY DON\'T !! ###'.PHP_EOL;
    }
}

$defaultUserName = 'binahayat00';
$input = readline("Enter your username of Github(default = $defaultUserName):");
$userName = strlen($input) > 0 ? $input : $defaultUserName;

$git = new Git($userName);

$git->showFirstMessage();

print_r($git->whoDoNotFollowYouByKey());


