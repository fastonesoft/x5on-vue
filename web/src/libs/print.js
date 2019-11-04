import html2canvas from 'html2canvas';

let printForm = function(title, imgData) {

    let printWin = window.open();
    printWin.document.write(`<head><title>${title}</title></head>`)
    printWin.document.write(`<img src='${imgData}' style="width:100%; height:100%;" />`);

    setTimeout(function(){
        printWin.print();
        printWin.close();
    }, 500)

}

let printImage = function(domId, title, width, height) {

    html2canvas(domId, {
        backgroundColor: null,
        width,
        height,
    }).then(canvas => {
        let imgData = canvas.toDataURL("image/png")
        printForm(title, imgData)
    })

}

export default {printImage};