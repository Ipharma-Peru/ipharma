
let boton = document.querySelector('#guardar')
boton.onclick = (e) => { 
    let url='/home'
    let formData = new FormData()
    formData.append('colores', atraparColores())
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: formData
    }).then(response=>{
        
    }).catch(error => {
        
    })
}

function atraparColores() {
    let seleccionados=[]
    var select = document.getElementById("colores");
    var options = select.options;

    for (var i = 0; i < options.length; i++) {
        if (options[i].selected) {
            console.log(options[i].value);
            seleccionados.push(options[i].value)
        }
    }
    return seleccionados
}