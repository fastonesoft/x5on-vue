let printImage = function(title, imageData) {
    let printWin = window.open();
    printWin.document.write(`<head><title>${title}</title></head>`)
    printWin.document.write(`<img src='${imageData}' />`);

    setTimeout(function(){
        printWin.print();
    }, 500)

    // return false;
}

export default {printImage};