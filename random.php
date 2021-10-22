<?php
/*
lets create a random nft with unique attributes

We are going to make a random monster truck
it will have attributes of: tires, color1, color2, pinstripes, facial hair, hat, eye color
tires:            color1:       color2:       pinstrips:           facial hair:  hat:                   eye color:   glasses:
low profile,      random color  random color  normal random color  handlebars    amish had              random 1-25  raybans
aqua treaders,    1-40          1-40          lightning            goatee        ball cap random color               cool oakleys
white walls,                                  dotted               jesus beard   football helmet                     nerd glasses
Mudders,                                      flames               amish beard   hockey helmet                       smart glasses random color 1-10
Water skies,                                  racing strips,       none          fishing hat                         sunglasses
tank tracks,                                                                     stocking hat
                                                                                 santa hat
 */

class Image {
    public $primaryColor = "";
    public $secondaryColor = "";
    public $pinStrips = "";
    public $facialHair = "";
    public $tires = "";
    public $hat = "";
    public $eyeColor = "";
    public $glasses = "";
}


class Collection {
    public $items = array();
}

class Item {
    public $name = "";
    public $rarity = 0.0; // out of 100%, if you want an item to show up 0.3% of the time, put in 0.3
    public $desc = "";
    public $key = "";
    function __construct($name, $key, $rarity, $desc) {
        $this->name = $name;
        $this->rarity = $rarity;
        $this->desc = $desc;
        $this->key = $key;
    }

    // if the above fields were private, you would use the two methods below
    // to get and set the value of the property *** We just call the varibles
    // becuase they are delcared as public and not private
    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }

        return $this;
    }
}
$tiresList = new Collection();
//array_push($tiresList->items, new Item("Low Profile", "lp", 29.5, "Low Profile Tires have a 10% chance at rarity"));
array_push($tiresList->items, new Item("Aqua Treaders", "aq", 10, "Aqua Treaders Tires have a 10% chance at rarity"));
array_push($tiresList->items, new Item("White Walls", "ww", 30, "White Wall Tires have a 10% chance at rarity"));
array_push($tiresList->items, new Item("Mudders", "m", 25, "Mudders have a 25% chance at rarity"));
array_push($tiresList->items, new Item("Water Skies", "ws", 0.5, "Water Skies have a 0.5% chance at rarity"));
array_push($tiresList->items, new Item("Tank Tracks", "tt", 5, "Tank Tracks have a 5% chance at rarity"));

$primaryCarColorList = new Collection();
array_push($primaryCarColorList->items, new Item("Blue", "blue", 20, "Blue"));
array_push($primaryCarColorList->items, new Item("Red", "red", 20, "Red"));
array_push($primaryCarColorList->items, new Item("White", "white", 20, "white"));
array_push($primaryCarColorList->items, new Item("Yellow", "yellow", 5, "Yellow"));
array_push($primaryCarColorList->items, new Item("Green", "green", 5, "Green"));
array_push($primaryCarColorList->items, new Item("Silver", "silver", 5, "Silver"));
array_push($primaryCarColorList->items, new Item("Gray", "gray", 5, "Gray"));  // 80%
//array_push($primaryCarColorList->items, new Item("Purple", "purple", 2.5, "Purple"));
//array_push($primaryCarColorList->items, new Item("Black", "black", 2.5, "Black"));
array_push($primaryCarColorList->items, new Item("Gold", "gold", 2.5, "gold"));
array_push($primaryCarColorList->items, new Item("Brown", "brown", 2.5, "brown")); // 90%
//array_push($primaryCarColorList->items, new Item("Lime", "lime", 2.5, "lime"));
//array_push($primaryCarColorList->items, new Item("Orange", "orange", 2.5, "orange")); // 95%
array_push($primaryCarColorList->items, new Item("Bone", "bone", 1, ""));
//array_push($primaryCarColorList->items, new Item("Mint", "mint", 1, ""));
//array_push($primaryCarColorList->items, new Item("Pink", "pink", 0.1, "")); // 98%
array_push($primaryCarColorList->items, new Item("Chartreuse", "chartreuse", 0.5, ""));
//array_push($primaryCarColorList->items, new Item("Platinum", "platinum", 0.5, ""));
array_push($primaryCarColorList->items, new Item("Diamond", "diamond", 0.5, ""));
//array_push($primaryCarColorList->items, new Item("Myrtle green", "myrtle", 0.5, ""));


$primaryCarColor = array("", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", );
$secondaryCarColor = array("", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", );
$pinStrips = array("normal", "lightning", "dotted", "flames", "racing strips", "dashed");
$facialHair = array("handlebars", "goatee", "jesus beard", "amish beard", "none", "fu man chu");
$hat = array("amish hat", "ball cap", "football helmet", "hockey helmet", "fishing hat", "stocking hat", "santa hat");
$eyeColor = array("", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", );
$glasses = array("raybans", "cool oakleys", "nerd glasses", "smart glasses", "sunglasses");

$tiresShuffledList = initializeCounts($tiresList);
$tiresTotal = initializeTotalArray($tiresList);
$primaryCarColorShuffledList = initializeCounts($primaryCarColorList);
$primaryCarColorTotal = initializeTotalArray($primaryCarColorList);

$secondaryCarColorTotal = array();
$pinStripsTotal = array();
$facialHairTotal = array();
$hatTotal = array();
$eyeColorTotal = array();
$glassesTotal = array();

function initializeCounts($collection) {
    $retVal = array();
    $items = $collection->items;
    foreach($items as $item) {
        $totalEntries = $item->rarity * 100;
        for ($i = 0; $i < $totalEntries; $i++) {
            array_push($retVal, $item);
        }
    }

    $shuffleAmount = rand(1, 100);
    for ($i = 0; $i < $shuffleAmount; $i++) {
        shuffle($retVal);
    }
    return $retVal;
}

function initializeTotalArray($collection) {
    $retVal = array();
    $items = $collection->items;
    foreach ($items as $item) {
        $retVal[$item->key] = 0;
    }
    return $retVal;
}


$loopTotal = 100;
$images = array();
for ($i = 0; $i < $loopTotal; $i++) {
    // get the tires
    $key = array_rand($tiresShuffledList);
    $tire = $tiresShuffledList[$key];
    $id = $tire->key;

    $tiresTotal[$id] = $tiresTotal[$id] + 1;

    // get the primary color
    $key = array_rand($primaryCarColorShuffledList);
    $primaryColor = $primaryCarColorShuffledList[$key];
    $id = $primaryColor->key;

    $primaryCarColorTotal[$id] = $primaryCarColorTotal[$id] + 1;

    $image = new Image();
    $image->tires = $tire->key;
    $image->primaryColor = $primaryColor->key;
    array_push($images, $image);

}
?>

<table>
    <tr>
        <td style="vertical-align: top;">
            <table border="1" cellpadding="5" cellspacing="0">
                <tr>
                    <td colspan="4"><strong>Tires Totals</strong></td>
                </tr>
                <tr>
                    <td><strong>Name</strong></td>
                    <td><strong>Rarity</strong></td>
                    <td><strong>Count</strong></td>
                    <td><strong>% of Total</strong></td>
                </tr>

                <?php
                $tires = $tiresList->items;
                foreach ($tires as $tire) {
                    $total = $tiresTotal[$tire->key]/$loopTotal*100;
                    ?>
                    <tr>
                        <td><?= $tire->name ?></td>
                        <td><?= $tire->rarity ?>%</td>
                        <td><?= $tiresTotal[$tire->key] ?></td>
                        <td><?= round($total, 1) ?>%</td>
                    </tr>
                    <?php
                }
                ?>

            </table>
        </td>
        <td>
            <table border="1" cellpadding="5" cellspacing="0">
                <tr>
                    <td colspan="4"><strong>Primary Car Color Totals</strong></td>
                </tr>
                <tr>
                    <td><strong>Name</strong></td>
                    <td><strong>Rarity</strong></td>
                    <td><strong>Count</strong></td>
                    <td><strong>% of Total</strong></td>
                </tr>

                <?php
                $primaryCarColors = $primaryCarColorList->items;
                foreach ($primaryCarColors as $item) {
                    $total = $primaryCarColorTotal[$item->key]/$loopTotal*100;
                    ?>
                    <tr>
                        <td><?= $item->name ?></td>
                        <td><?= $item->rarity ?>%</td>
                        <td><?= $primaryCarColorTotal[$item->key] ?></td>
                        <td><?= round($total, 1) ?>%</td>
                    </tr>
                    <?php
                }
                ?>

            </table>
        </td>
    </tr>
</table>
