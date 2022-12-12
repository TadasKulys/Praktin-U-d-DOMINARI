<?php namespace Tvmaze;?>

<!DOCTYPE HTML>

<form method="GET">
    <p>Pavadinimas: </p><input name="searchName" required>
    <p>Aprašas: </p><input name="searchSummary">
    <p>Trukmė: </p><input name="searchRuntime">
    <input type="submit">
</form>

<?php
include('Show.php');
class Client
{

    protected $endpoint = "http://api.tvmaze.com/";

    protected function buildUrl($subUrl)
    {
        return $this->endpoint . trim($subUrl, "/");
    }

    protected function makeRequest($subUrl)
    {
        $url = $this->buildUrl($subUrl);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);
        curl_close($curl);

        $response = json_decode($response, true);

        return $response;
    }

    public function searchShow($title)
    {
        $response = $this->makeRequest('search/shows?q=' . $title);

        if (!$response) {
            return [];
        }

        $shows = [];
        foreach ($response as $response_item) {
            $shows[] = Show::apiConnect($response_item["show"]);
        }

        return $shows;
    }
    public function foreachLoopN($shows){
        foreach ($shows as $show) {
            echo "<img src='$show->image'/><br>
            Pavadinimas: $show->name <br>
            Trukmė: $show->runtime min.<br>
            Žanras: $show->genres <br>
            Laidos tipas: $show->type <br>
            $show->summary <br> ";
        }
    }
    public function foreachLoopNS($shows){
        foreach ($shows as $show) {
            if(stripos($show->summary,$_GET['searchSummary'])){
                {
                    echo "<img src='$show->image'/><br>
                    Pavadinimas: $show->name <br>
                    Trukmė: $show->runtime min.<br>
                    Žanras: $show->genres <br>
                    Laidos tipas: $show->type <br>
                    $show->summary <br> ";
                }
            }
        }
    }
    public function foreachLoopNTS($shows){
        //IFs tam kad nerodytu error Warning: Undefined array key
        if(isset($_GET["searchRuntime"])){
            $time = preg_replace('/[><]/','',$_GET['searchRuntime']);
        }
        if(isset($_GET["searchRuntime"])){
            $symbols = preg_replace ('/[0-9]/','',$_GET['searchRuntime']);
        }
        
        foreach($shows as $show){
            if(str_contains($_GET['searchRuntime'],'>') and $time>$show->runtime and !is_null($show->runtime)){
                echo "<img src='$show->image'/><br>
                Pavadinimas: $show->name <br>
                Trukmė: $show->runtime min.<br>
                Žanras: $show->genres <br>
                Laidos tipas: $show->type <br>
                $show->summary <br> ";
            }
            elseif(str_contains($_GET['searchRuntime'],'<') and $time<$show->runtime and !is_null($show->runtime)){
                echo "<img src='$show->image'/><br>
                Pavadinimas: $show->name <br>
                Trukmė: $show->runtime min.<br>
                Žanras: $show->genres <br>
                Laidos tipas: $show->type <br>
                $show->summary <br> ";
            }  
        }
    }
}

$client = new Client();

//IF tam kad nerodytu error Warning: Undefined array key
if(isset($_GET["searchName"])){
$shows = $client->searchShow($_GET["searchName"]);
}


//Name search
if(isset($_GET['searchName']) and empty($_GET['searchSummary']) and empty($_GET['searchRuntime'])){
    $loop = $client->foreachLoopN($shows);
}
//Name+summary search
elseif(isset($_GET['searchName']) and isset($_GET['searchSummary']) and empty($_GET['searchRuntime'])){
    $loop = $client->foreachLoopNS($shows);
}
//Name+time+summary search
elseif(isset($_GET['searchName']) and isset($_GET['searchRuntime']) and isset($_GET['searchSummary'])){
    $loop = $client->foreachLoopNTS($shows);
}
//Name+time-summary search
elseif(isset($_GET['searchName']) and isset($_GET['searchRuntime']) and empty($_GET['searchSummary'])){
    $loop = $client->foreachLoopNTS($shows);
}

?>