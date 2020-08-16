<html>
<head>
    <title>Noteapp</title>
    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
    <script src = './js/treeview.js' ></script>
    <script src = './js/app.js' ></script>
    <link rel="stylesheet" type="text/css" href="css/treeview.css">
    <script src='https://kit.fontawesome.com/a076d05399.js'></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js" integrity="sha384-LtrjvnR4Twt/qOuYxE721u19sVFLVSA4hf/rRt6PrZTmiPltdZcI7q7PXQBYTKyf" crossorigin="anonymous"></script>
</head>
<body>
    <style>
        .sidebar{

        }
        .mainframe{
            background-color:white;
            width:100%;
            height:95%;
            margin : 4px;
            border : 1px solid;
        }
        .titlebar {
            background-color:white;
            width:93%;
            height:30px;
            border : 1px solid;
            margin-top : 3%;
        }
        .status-block {
            height:20%;
            border: 1px solid;
        }
        .menu-nav {
            height:80%;
            border: 1px solid;
        }
        table {
            height:100%;
            width:100%;
        }

        .menu_note {
            padding: 0px;
            margin : 5px;
            cursor: pointer;
            
        }

        .btn {
            margin: 1px;
        }
    </style>
    <!-- cursor: url("https://s3-us-west-2.amazonaws.com/s.cdpn.io/9632/happy.png"), auto;  for custom cursor -->
    <?php

    include_once 'autoload/autoloader.php';

    $menu = new App($config['absolute_path']);
    $menuHTML = $menu->render_menu();
    

    ?>

    <div>
        <table border='1'>
            <tr>
                <td style="width:20%">
                    <div class="status-block" id="status_block">
                        <button id="createNewCategory" class="btn btn-primary">Create New Category</button>
                        <button id="createNewNote" class="btn btn-primary"><span>Create New Note</span> <span></span></button>   
                        <div>Filter : <input type="text" id="search_box" /></div>
                    
                    </div>
                    <div class="menu-nav" id="menu_nav">

                        <?php echo $menuHTML ?>

                    </div>
                </td>
                <td style="width:80%;padding:15px">
                    <div style="margin:5px"><span style="width:5%">Title : </span><input type="text" class="titlebar" id="titlebar" /></div>
                    <div style="margin:5px"><span>Current Directory : </span><span id="directory"></span></div>
                    <div><button id="save" class="btn btn-primary">Save</button></div>
                    <div class="mainframe" id="mainframe" contentEditable = true></div>  
                </td>
            </tr>
        </table>

    
    
    </div>


    <script>
        
        $(document).ready(function() {

            window.addEventListener("paste", function(thePasteEvent){
                // Use thePasteEvent object here ...
                console.log(document.getElementById('mainframe').innerHTML)
            }, false);

            $("#mainframe").keyup(function() {
                //textarea.update();





            });

            $("#save").click(function() {
                NoteApp.save();

            });

            $("span[name='menu_category']").on("click",function() {
                // clear up all the colors of all menu
                $.each(document.getElementsByName("menu_category"), function() {this.style.backgroundColor = "white"});
                $.each(document.getElementsByName("menu_category"), function() {this.style.color = "black"});
                //highlight current clicked menu colour
                NoteApp.current_category = this.innerText;
                this.style.backgroundColor = "black";
                this.style.color = "white";
            });

            $("span[name='menu_note']").click(function() {
                NoteApp.menu_click(this);
            });

            $("#createNewNote").click(function() {
                NoteApp.createNewNote();
            });
            $("#createNewCategory").click(function() {
                NoteApp.createNewCategory();
            });

            $("#search_box").keyup(function() {
                NoteApp.search($("#search_box").val())
            });

        })


    </script>



</body>
</html>

