let NoteApp = {

    current_file : "",
    current_category : "",

    menu_click : function(menu) {
        let note = menu.innerText;
        let category = menu.parentNode.parentNode.parentNode.getElementsByClassName('caret')[0].innerText;
        this.sendReq('./post/post.php', { action : "open" , note : note , category : category }, function(data) {
            console.log(data)
            document.getElementById('mainframe').innerHTML = data;
            document.getElementById('titlebar').value = note;
            NoteApp.current_file = note;
            document.getElementById('directory').innerText = NoteApp.current_category + '/' + NoteApp.current_file;
        }) ;
    },
    save : function() {
        let htmlToSave = $("#mainframe").html();
        let titleToSave = $("#titlebar").val();
        if (titleToSave === "") {
            alert("Please input title!")
            return;
        }
        if (!NoteApp.namingValidation(titleToSave)) {
            alert("Illegal name")
            return;
        }
        titleToSave = NoteApp.sanitizeName(titleToSave);
        let currentDir = this.current_category + "/" + (this.current_file ? this.current_file : titleToSave);
        //if (this.current_file === "") {  // if this.current_file has no title, add title to it
          //  currentDir += titleToSave;
        //}
        let saveDir = this.current_category + "/" + titleToSave;
        this.sendReq('./post/post.php', { action : "save" , body : htmlToSave , title : titleToSave, currentDir : currentDir, saveDir : saveDir}, function(data) {
           if (data == "success") {
            //refresh
            NoteApp.sendReq('./post/post.php', { action : "refreshMenu" }, function(data) { 
                if (data != 'error') {
                    $("#menu_nav").html(data); 
                    NoteApp.treeview();
                    NoteApp.applyMenuEvents();
                } else {
                    
                }
            })
           } else {

           }
       }) ;
    },
    sendReq : function(destination, sendData, callback) {
        $.post( destination, sendData ) 
        .done(function( data ) {
            try{
                parsedData = JSON.parse(data);   
                callback(parsedData);               
            } catch(error) {
                callback(data);     
            }

        })
    },

    createNewNote : function() {

        if (this.current_category) {
            $("#titlebar").val("");
            $("#mainframe").html("");
            $("#directory").html(this.current_category + "/" );
            this.current_file = "";
        } else {
            let answer = prompt("Please input new category name");
            if (answer) {
                if (!NoteApp.namingValidation(answer)) {
                    alert("Illegal name")
                    return;
                }
                answer = NoteApp.sanitizeName(answer);
                NoteApp.sendReq('./post/post.php', { action : "newCategory" , name : answer }, function(data) {
                    if (data === "success") {
                        alert(answer + " successfully created");
                        NoteApp.current_category = answer;
                        $("#titlebar").val("");
                        $("#mainframe").html("");
                        $("#directory").html(NoteApp.current_category + "/" );
                        this.current_file = "";
                        // refresh menubar
                        NoteApp.sendReq('./post/post.php', { action : "refreshMenu" }, function(data) { 
                            if (data != 'error') {
                                $("#menu_nav").html(data); 
                                NoteApp.treeview();
                            } else {
                                
                            }
                        })
                        
                    } else {
                        alert(data);
                    }
                });
            }
        }



    },

    createNewCategory : function() {
        let newLi = document.createElement("li")
        newLi.innerHTML = `<span class="caret" name="menu_category"><div contentEditable=true style="border:1px solid black;display:inline-block;width:50%;"></div></span>`;
        
        newLi.addEventListener("focusout", function() {
            if (newLi.innerText != "") {
                // create new category
                if (!NoteApp.namingValidation(newLi.innerText)) {
                    alert("Illegal name")
                    return;
                }
                newLi.innerText = NoteApp.sanitizeName(newLi.innerText)
                NoteApp.sendReq('./post/post.php', { action : "newCategory" , name : newLi.children[0].children[0].innerText }, function(data) {
                    if (data === "success") {
                        NoteApp.current_category = newLi.children[0].children[0].innerText;
                        newLi.children[0].children[0].contentEditable = false;
                        newLi.children[0].children[0].style.border = "0px";
                    } else {
                        alert(data)
                        newLi.remove();
                    }
                }) ;
            }
        })


        document.getElementById("myUL").children[0].append(newLi)
        newLi.children[0].children[0].focus();
    },

    treeview : function() {
        var toggler = document.getElementsByClassName("caret");
        var i;
            
        for (i = 0; i < toggler.length; i++) {
          toggler[i].addEventListener("click", function() {
            try {
              this.parentElement.querySelector(".nested").classList.toggle("active");
              this.classList.toggle("caret-down");
            } catch {
                this.classList.toggle("caret-down");
            }
              
            
          });
        }
      
    },

    applyMenuEvents : function() {
        var toggler = document.getElementsByName("menu_category");
        for (let i = 0;i< toggler.length;i++) {
            ele = toggler[i];
            ele.addEventListener("click", function() {
                // clear up all the colors of all menu
                $.each(document.getElementsByName("menu_category"), function() {this.style.backgroundColor = "white"});
                $.each(document.getElementsByName("menu_category"), function() {this.style.color = "black"});
                //highlight current clicked menu colour
                NoteApp.current_category = this.innerText;
                this.style.backgroundColor = "black";
                this.style.color = "white";                
            })
        }
        $("span[name='menu_note']").click(function() {
            NoteApp.menu_click(this);
        });
            


    },


    search : function(text) {

        // expand all category
        $.each(document.getElementById('myUL').getElementsByTagName('ul'), function() { this.classList.toggle("active") });
        // change caret
        $.each(document.getElementById('myUL').getElementsByClassName('caret'), function() { this.classList.toggle("caret-down") });

        // if nothing in search , collapsed all
        if (text === "") {
            // change caret
            $.each(document.getElementById('myUL').getElementsByClassName('caret'), function() { this.classList.toggle("caret-up") }); 
            //restore visibility to all li
            $.each(document.getElementById('myUL').getElementsByClassName('menu_note'), function() { 
                this.style.display = "inline";
                this.parentNode.style.display = "block";
            }); 
        } else {
            let lis = document.getElementById('myUL').getElementsByClassName('menu_note');

            for (let i = 0 ; i < lis.length; i ++) {
                lis[i].style.display = "inline"
                lis[i].parentElement.style.display = "block"
                if (lis[i].innerText.indexOf(text) > -1) {
                    
                } else {
                    lis[i].style.display = "none"
                    lis[i].parentElement.style.display = "none"
                }
            }
        }

    },

    namingValidation : function(text) {
        // check for illegal characters
        let forbidden = ['$', ">", "<", "/" , "|" , "?", ":" , "*" , String.fromCharCode(92)]
        for (let i  = 0 ; i<forbidden.length;i ++) {
            if (text.includes(forbidden[i])) return false;
        }

        return true;
    },
    sanitizeName : function(text) {
        return text.replace(/\n/g, "");
    }

}