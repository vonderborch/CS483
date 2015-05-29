
var mode = "small";
var boxes = Array();

function generatetitlesandbodies()
{
    for (var i = 0; i < 64; i++)
    {
        $.getJSON("http://www.randomtext.me/api/lorem/ul-1/1-1", function (data)
        {
            for (var j = 0; j < boxes.length; j++)
            {
                var nodes = $(boxes[j]).children().children();
                if (nodes[0].innerHTML == 'box')
                {
                    nodes[0].innerHTML = $("<div />").html(data.text_out).text();
                    break;
                }
            }
        });
    }
    for (var i = 0; i < 64; i++)
    {
        $.getJSON("http://www.randomtext.me/api/lorem/ul-1/5-100", function (data)
        {
            for (var j = 0; j < boxes.length; j++)
            {
                var nodes = $(boxes[j]).children().children();
                if (nodes[1].innerHTML == 'box')
                {
                    nodes[1].innerHTML = $("<div />").html(data.text_out).text();
                    break;
                }
            }
        });
    }
}

$(document).ready(function ()
{
    $('body').css('background-color', 'black');
    getsizeandmode();
    generatetitlesandbodies();

    $("#dialog").dialog({
        autoOpen: false,
        draggable: false,
        resizable: false,
        width: 400,
        modal: true
    });

    var numboxes = 34 + Math.floor(Math.random() * 30);
    for (var i = 0; i < numboxes; i++)
    {
        var title = 'box';
        var body = 'box';
        $box = $('<div><div class="box_content"><h1>' + title + '</h1>\n<h2>' + body + '</h2></div></div');
        assignbackground($box);
        assignboxtype($box);
        $box.on("click", function ()
        {
            var color = $(this).css('backgroundColor');
            var nodes = $(this).children().children();
            var ttl = nodes[0].innerHTML + "   ";
            var txt = nodes[1].innerHTML;
            // set body text
            var diag = $("#dialog").children();
            diag[0].innerHTML = txt;
            $(".ui-dialog").css('background-color', tohex(color));
            // set title
            $("#dialog").dialog("option", "title", ttl)
            // open!j
            $("#dialog").dialog("open");
            $('#dialog').dialog();
            return false;
        });
        boxes.push($box);
    }

    distributeboxes();
});

function tohex(input)
{
    var parts = input.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
    delete (parts[0]);
    for (var i = 1; i <= 3; ++i)
    {
        parts[i] = parseInt(parts[i]).toString(16);
        if (parts[i].length == 1) parts[i] = '0' + parts[i];
    }
    return '#' + parts.join('');
}

$(window).bind("debouncedresize", function ()
{
    getsizeandmode();
    distributeboxes();
});

function distributeboxes()
{
    var div = document.getElementById('content');
    if (div)
    {
        div.parentNode.removeChild(div);
    }

    var $maincontent = $('<div class="maincontent" id="content"></div>').appendTo('body');
    var contentboxes = 1;
    if (mode == "medium")
    {
        contentboxes = 2;
    }
    else if (mode == "large")
    {
        contentboxes = 3;
    }
    else if (mode == "giant")
    {
        contentboxes = 4;
    }
    else if (mode == "unlimited")
    {
        contentboxes = ($(boxes).length * 192.5) / $(this).height();
    }

    var itemsadded = cleanboxes();
    var rows = calcrows() / contentboxes;
    var k = 0;
    var totalitems = 0;
    var columns = Array();
    var rowremainder = rows - Math.floor(rows);
    if (rowremainder == 0.25)
    {
        rows = Math.floor(rows) + 1;
    }
    else if (rowremainder == 0.5)
    {
        rows = Math.ceil(rows);
    }
    else if (rowremainder == 0.75)
    {
        rows = Math.floor(rows) - 1;
    }
    else
    {
        rows = Math.ceil(rows);
    }
    for (var i = 0; i < contentboxes; i++)
    {
        var colname = 'col' + i;
        var $column = $('<div class="content_box content_column" id="' + colname + '"></div>').appendTo($maincontent);

        for (var j = 0; j < itemsadded.length; j++)
        {
            if (itemsadded[j] == false)
            {
                k = j;
                break;
            }
        }

        for (var j = 0; j < rows; j++)
        {
            // get first box...
            while (itemsadded[k] == true)
            {
                k = k + 1;
            }
            if (k >= itemsadded.length)
            {
            }
            else
            {
                var $row = $('<div class="content_row" id="' + colname + 'row' + j + '"></div>').appendTo($column);

                var boxclass = $(boxes[k]).attr('class');
                if (boxclass == "box_double")
                {
                    $(boxes[k]).appendTo($row);
                    itemsadded[k] = true;
                    totalitems++;
                    continue;
                }
                else if (boxclass == "box_big")
                {
                    $(boxes[k]).appendTo($row);
                    itemsadded[k] = true;
                    totalitems++;
                    continue;
                }
                else
                {
                    $(boxes[k]).appendTo($row);
                    itemsadded[k] = true;
                    totalitems++;
                }

                // get second...
                while (($(boxes[k]).attr('class') != "box_regular" && k < $(boxes).length) || itemsadded[k] == true)
                {
                    k = k + 1;
                }
                $(boxes[k]).appendTo($row);
                itemsadded[k] = true;
                totalitems++;
            }
        }

        columns.push($column);
    }

    // make sure balancing is good...
    if (mode == "medium")
    {
        var col1 = document.getElementById("col0");
        var col2 = document.getElementById("col1");
        $(col1).filter(function ()
        {
            return $(this).text().trim() == "";
        }).remove();
        $(col2).filter(function ()
        {
            return $(this).text().trim() == "";
        }).remove();
        var col1length = calcweight(col1);
        var col2length = calcweight(col2);
        var dif = Math.abs(col1length - col2length) / 2;
        if (col1length > col2length)
        {
            for (var i = 0; i < dif; i++)
            {
                $(col2).prepend(col1.lastChild);
            }
        }
        else if (col2length > col1length)
        {
            for (var i = 0; i < dif; i++)
            {
                $(col1).prepend(col2.lastChild);
            }
        }
    }
    else if (mode == "large")
    {
        var col1 = document.getElementById("col0");
        var col2 = document.getElementById("col1");
        var col3 = document.getElementById("col2");
        $(col1).filter(function ()
        {
            return $(this).text().trim() == "";
        }).remove();
        $(col2).filter(function ()
        {
            return $(this).text().trim() == "";
        }).remove();
        $(col3).filter(function ()
        {
            return $(this).text().trim() == "";
        }).remove();
        var col2length = calcweight(col2);
        var col3length = calcweight(col3);
        var col23dif = col2length - col3length;
        var itemstomove = Math.floor(col23dif / 2);
        for (var i = 0; i < itemstomove; i++)
        {
            $(col2).prepend(col1.lastChild);
        }
        for (var i = 0; i < itemstomove + 1; i++)
        {
            $(col3).prepend(col2.lastChild);
        }
    }
    else if (mode == "giant")
    {
        var col1 = document.getElementById("col0");
        var col2 = document.getElementById("col1");
        var col3 = document.getElementById("col2");
        var col4 = document.getElementById("col3");
        $(col1).filter(function ()
        {
            return $(this).text().trim() == "";
        }).remove();
        $(col2).filter(function ()
        {
            return $(this).text().trim() == "";
        }).remove();
        $(col3).filter(function ()
        {
            return $(this).text().trim() == "";
        }).remove();
        $(col4).filter(function ()
        {
            return $(this).text().trim() == "";
        }).remove();
        var col3length = calcweight(col3);
        var col4length = calcweight(col4);
        var col34dif = col3length - col4length;
        var itemstomove = Math.floor(col34dif / 3);
        for (var i = 0; i < itemstomove; i++)
        {
            $(col2).prepend(col1.lastChild);
        }
        for (var i = 0; i < itemstomove + 1; i++)
        {
            $(col3).prepend(col2.lastChild);
        }
        for (var i = 0; i < itemstomove + 1; i++)
        {
            $(col4).prepend(col3.lastChild);
        }
    }
}

function calcweight(column)
{
    var weight = column.childElementCount;

    for (var k = 0; k < column.childElementCount; k++)
    {
        var item = column.children[k];
        var classname = item.attr('class');

        if (classname == "big")
        {
            weight--;
        }
    }

    return weight;
}

function cleancolumn(column)
{
    var childrennodes = column.childElementCount;
    column.filter(function ()
    {
        return $(this).text().trim() == "";
    }).remove();
    childrennodes = column.childElementCount;
}

function getnextitem(itemstoadd)
{
    for (var x = 0; x < itemstoadd.length; x++)
    {
        if (itemstoadd[x] == false)
        {
            return x;
        }
    }
    return -1;
}

function cleanboxes()
{
    var toreturnarray = Array();
    for (var i = 0; i < boxes.length; i++)
    {
        toreturnarray.push(false);
        var boxclass = $(boxes[i]).attr('class');
        if (mode == "small" && boxclass == "box_double")
        {
            changeclass($(boxes[i]), "box_double", "box_fake_single_was_double");
        }
        else if (mode != "small" && boxclass == "box_fake_single_was_double")
        {
            changeclass($(boxes[i]), "box_fake_single_was_double", "box_double");
        }
        else if ((mode == "small" || mode == "medium") && boxclass == "box_big")
        {
            changeclass($(boxes[i]), "box_big", "box_fake_single_was_big");
        }
        else if ((mode == "large" || mode == "giant") && boxclass == "box_fake_single_was_big")
        {
            changeclass($(boxes[i]), "box_fake_single_was_big", "box_big");
        }
    }
    return toreturnarray;
}

function calcrows()
{
    var rowcalcs = 0.0;

    for (var i = 0; i < $(boxes).length; i++)
    {
        var classname = $(boxes[i]).attr('class');
        if (classname == "box_regular" || mode == "small" || (mode == "medium" && classname == "box_big"))
        {
            rowcalcs = rowcalcs + 0.5;
        }
        else if (classname == "box_double")
        {
            rowcalcs = rowcalcs + 1;
        }
        else if (classname == "box_big")
        {
            rowcalcs = rowcalcs + 2;
        }
    }

    return rowcalcs;
}

function changeclass($box, originalClass, newClass)
{
    $($box).removeClass(originalClass);
    $($box).addClass(newClass);
}
function assignboxtype($box)
{
    var types = ['box_regular', 'box_regular', 'box_regular', 'box_regular', 'box_regular', 'box_regular', 'box_double']
    
//    var types = ['box_regular', 'box_regular', 'box_regular', 'box_regular', 'box_regular', 'box_regular', 'box_double',
//    'box_regular', 'box_regular', 'box_regular', 'box_regular', 'box_regular', 'box_regular', 'box_double',
//    'box_big'];

    $($box).addClass(types[Math.floor(Math.random() * types.length)]);
}
function assignbackground($box)
{
    $($box).css('background-color', '#000');

    var bgtypechoice = ['color', 'image', 'image', 'image', 'image', 'image', 'image', 'image', 'image', 'image']; // 90% chance for using an image
    var choice = bgtypechoice[Math.floor(Math.random() * bgtypechoice.length)];
    if (choice == "color")
    {
        // assign color...
        var colors = ['#2672EC', '#00A300', '#97009F', '#094DB5', '#DA532C', '#AF1A3F', '#613CBC', '#008AD2'];
        $($box).css('background-color', colors[Math.floor(Math.random() * colors.length)]);
    }
    else
    {
        // assign image...
        var image = 'images/tiles/' + Math.floor(Math.random() * 128) + '.jpg';
        $($box).css('background-image', 'url("' + image + '")');

        var showtextlist = ['yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'no', 'no']; // 80% yes, 20% no
        var showtext = showtextlist[Math.floor(Math.random() * showtextlist.length)];
        if (showtext == 'no')
        {
            var $innercontent = $($box).children();
            $($innercontent[0]).removeClass('box_content');
            $($innercontent[0]).addClass('box_hidden_content');
        }
    }
}

function getsizeandmode()
{
    // get size and set mode...
    var width = $(this).width();
    var height = $(this).height();

    var update = false;
    var newmode = "small";
    if (width >= 840)
    {
        newmode = "medium";
    }
    if (width >= 1260)
    {
        newmode = "large";
    }
    if (width >= 1680)
    {
        newmode = "giant";
    }
    if (width >= 2100)
    {
        newmode = "unlimited";
    }
    if (mode != newmode)
    {
        mode = newmode;
        update = true;
    }

    if (update == true)
    {
        if (mode == "medium")
        {
            $('body').css('max-width', 1259);
            $('body').css('background-image', 'url("images/bg.png")');
            $('body').css('background-color', '#17064A');
        }
        else if (mode == "large")
        {
            $('body').css('max-width', 1799);
            $('body').css('background-image', 'url("images/bg.png")');
            $('body').css('background-color', '#17064A');
        }
        else if (mode == "giant")
        {
            $('body').css('max-width', 1920);
            $('body').css('background-image', 'url("images/bg.png")');
            $('body').css('background-color', '#17064A');
        }
        else if (mode == "unlimited")
        {
            $('body').css('max-width', $(this).width);
            $('body').css('background-image', 'url("images/bg.png")');
            $('body').css('background-color', '#17064A');
        }
        else
        {
            $('body').css('max-width', 480);
            $('body').css('background-image', '');
            $('body').css('background-color', 'black');
        }
    }
};