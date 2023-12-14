(() => {
  document.addEventListener("DOMContentLoaded", async () => {
    try {
      $.post("admin/ajax/contador_de_pagina.php?op=contador_de_pagina", { 'pagina_nombre': $(".name_page").text() }, function (e, status) {
        e = JSON.parse(e);  console.log(e);  
        if (e.status == true) {
          console.log("registrando visita");  
        } else {
          ver_errores(e);
        }       
      }).fail( function(e) { console.log(e); ver_errores(e); } );
    } catch (e) {
      console.log("Error registrando visita: " + e);
    }
  });
})();