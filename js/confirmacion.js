function confirmacion(e){
    if (confirm("¿Estas seguro de que deseas eliminar este Producto?")){
        return true;
    }
    else{
        e.preventDefault();
    }
}

let linkDelete = document.querySelectorAll(".delet");  

for (var i = 0; i < linkDelete.length; i++) {
    linkDelete[i].addEventListener('click', confirmacion);
}