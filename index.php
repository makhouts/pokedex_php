<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Pokedéx</title>
</head>
<body>
    <?php
        $pokemonId = $_GET['searchValue'];
        if (isset($pokemonId)) {
            $api_url = "https://pokeapi.co/api/v2/pokemon/$pokemonId";
        } else {
            $api_url = "https://pokeapi.co/api/v2/pokemon/1";
        };
       

        $json_data = file_get_contents($api_url);
        $response_data = json_decode($json_data);

        $img = $response_data->sprites->front_default;
        $title = $response_data->name;
        $id = $response_data->id;
        $moves = $response_data->moves;
        array_splice($moves, 4);

        /* adding the evolutions */

        $getEvoUrl = file_get_contents($response_data->species->url);
        $response_data_evo = json_decode($getEvoUrl);   
        $evolution_url = $response_data_evo->evolution_chain->url; 
        

    ?>
    <div class="container">
        <div class="pokedex">
            <img src="./assets/pokedex.png" alt="">
        </div>
        <div class="pokemon-information">
            <img id="image" src="<?php echo $img ?>" alt="pokemon">
            <span id="pokemonId"><?php echo $id ?></span>
            <div class="pokemon-info">
                <h1 id="pokemon-name"><?php echo $title ?></h1>
                <div class="moves">
                    <?php
                       foreach ($moves as $key=>$move) {
                        $x = $key + 1;
                        echo "<p id=move{$x}>{$move->move->name}</p>";
                       };
                    ?>
                </div>
            </div>
        </div>
        <div>
        <div class="border-shadow1"></div>
        <div class="border-shadow2"></div>
            <a href="?searchValue=<?php echo $id -1 ?>"><button class='button' type='submit' name='prevBtn' id="previous"></button></a>
            <a href="?searchValue=<?php echo $id +1 ?>"><button class='button' type='submit' name='nextBtn' id="next"></button></a>
        </div>
        <div>

        <form action="?searchValue=<?php echo $_GET['searchValue'] ?>" method='GET'>
            <input id="searchValue" name='searchValue' placeholder="Search Pokemon" type="text">
            <button id="searchBtn">Search</button>
        </form>
        </div>
    </div>
    <div id="evolutionImg">
        <?php
            $fetchEvolutions = file_get_contents($evolution_url);
            $evolutionData = json_decode($fetchEvolutions);
            $x = $evolutionData->chain->species->name;
            
            $getEvolutionImg = file_get_contents("https://pokeapi.co/api/v2/pokemon/$x");
            $imageData = json_decode($getEvolutionImg);

            $imgLink = $imageData->sprites->front_default
        ?>

        <a href="?searchValue=<?php echo $x ?>"><img class='evo1' src="<?php echo $imgLink ?>" alt=""></a>
    </div>
</body>
</html>