import html2canvas from 'html2canvas';

let printForm = function(title, imgData) {

    let printWin = window.open();
    printWin.document.write(`<head><title>${title}</title></head>`)
    printWin.document.write(`<div style="text-align: center;"><img src='${imgData}'/></div>`);

    setTimeout(function(){
        printWin.print();
        printWin.close();
    }, 500)

}

let printImage = function(domIdName, title) {
    let domId = document.getElementById(domIdName);
    html2canvas(domId, {
        backgroundColor: null,
    }).then(canvas => {
        let imgData = canvas.toDataURL("image/png")
        printForm(title, imgData)
    })

}

export default {printImage};