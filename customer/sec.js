
    var logBtn = document.getElementById("s1");
    var logBox = document.getElementById("d1");

    function f1(event){
        event.stopPropagation();
        display = logBox.style.display;
        if(display == "none")
            logBox.style.display = "flex";
        else 
            logBox.style.display = "none";
    }
    function f2(event){
        if (!logBox.contains(event.target)) {
            logBox.style.display = "none";
        }
    }

    logBtn.addEventListener("click", f1);
    document.addEventListener("click", f2); 
