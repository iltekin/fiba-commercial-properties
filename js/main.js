var ulke = "hepsi";
var kategori = "hepsi";
var gelecek = "kapali";

function guncelle() {
    $("#projects .col-3").fadeOut(300);
    $("#projects .col-3").promise().done(function () {

        $("#projects .col-3").each(function () {

            var thisKategori = $(this).data("kategori");
            var thisUlke = $(this).data("ulke");
            var thisGelecek = $(this).data("gelecek");

            if ((ulke === thisUlke || ulke === "hepsi") && (kategori === thisKategori || kategori === "hepsi") && (gelecek === "kapali" || (gelecek === "acik" && thisGelecek === "evet"))) {
                $(this).fadeIn(300);
            }

        });

    });
}

$(".default_option").click(function(){
    $(this).parent().toggleClass("active");
});

$(".check_option").click(function(){

    if(gelecek === 'acik'){
        gelecek = 'kapali';
    }else{
        gelecek = 'acik';
    }

    guncelle();

    $(this).parent().toggleClass("active").toggleClass("soldur");
});

$(".ulkeSec li").click(function(){

    ulke = $(this).data("ulke");
    guncelle();

    var cur = $(this).find("div p").html();
    $(this).parent().parent().find(".default_option li div p span").html(cur);
    $(this).parents(".select_wrap").removeClass("active");
});

$(".kategoriSec li").click(function(){

    kategori = $(this).data("kategori");
    guncelle();

    var cur = $(this).find("div p").html();
    $(this).parent().parent().find(".default_option li div p span").html(cur);
    $(this).parents(".select_wrap").removeClass("active");
});

$("#play-video").click(function(){
    openFullscreen();
});

$("#video").click(function(){
    closeFullscreen();
});

$(".backward-video").click(function(e){
    e.stopPropagation();
    e.preventDefault();
    backward();
});

/* Get the documentElement (<html>) to display the page in fullscreen */
var elem = document.documentElement;

/* View in fullscreen */
function openFullscreen() {

    $("#video").show();
    $("#video-player").trigger('play');

    if (elem.requestFullscreen) {
        elem.requestFullscreen();
    } else if (elem.webkitRequestFullscreen) { /* Safari */
        elem.webkitRequestFullscreen();
    } else if (elem.msRequestFullscreen) { /* IE11 */
        elem.msRequestFullscreen();
    }
}

/* Close fullscreen */
function closeFullscreen() {
    $("#video").hide();
    $("#video-player").trigger('pause');

    if (document.exitFullscreen) {
        document.exitFullscreen();
    } else if (document.webkitExitFullscreen) { /* Safari */
        document.webkitExitFullscreen();
    } else if (document.msExitFullscreen) { /* IE11 */
        document.msExitFullscreen();
    }
}

function backward(){
    $('#video-player').get(0).currentTime = 0;
}