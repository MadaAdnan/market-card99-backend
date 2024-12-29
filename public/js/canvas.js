
window.onload = canvas;
function canvas() {
    var canvasElement = document.getElementById('canvas');
    if (canvasElement && canvasElement.getContext("2d")) {
        var width = canvasElement.width;
        var height = canvasElement.height;
        var context = canvasElement.getContext('2d');
        var grd = context.createLinearGradient(0, 0, height, 0);
        grd.addColorStop(0, "#4fe1de");
        grd.addColorStop(1, "#0485c2");
        context.fillStyle = grd;
        context.beginPath();
        context.moveTo(0, 0);
        context.lineTo(0, height);
        context.quadraticCurveTo(width / 3, height / 5, width / 2, height / 2);
        context.quadraticCurveTo(width - 50, height, width, height - 20);
        context.lineTo(width, 0);
        context.fill();
        // context.stroke();

    }
}
