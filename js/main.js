var ulke = localStorage.getItem("ulke") || "hepsi";
var kategori = localStorage.getItem("kategori") || "hepsi";
var gelecek = localStorage.getItem("gelecek") || "kapali";

function guncelle() {
    $("#projects .col-12").fadeOut(300);
    $("#projects .col-12").promise().done(function () {

        $("#projects .col-12").each(function () {

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
        localStorage.setItem("gelecek", "kapali");
    }else{
        gelecek = 'acik';
        localStorage.setItem("gelecek", "acik");
    }

    guncelle();

    $(this).parent().toggleClass("active").toggleClass("soldur");
});

$(".ulkeSec li").click(function(){

    ulke = $(this).data("ulke");
    localStorage.setItem("ulke", ulke);
    guncelle();

    var cur = $(this).find("div p").html();
    $(this).parent().parent().find(".default_option li div p span").html(cur);
    $(this).parents(".select_wrap").removeClass("active");
});

$(".kategoriSec li").click(function(){

    kategori = $(this).data("kategori");
    localStorage.setItem("kategori", kategori);
    guncelle();

    var cur = $(this).find("div p").html();
    $(this).parent().parent().find(".default_option li div p span").html(cur);
    $(this).parents(".select_wrap").removeClass("active");
});

// onload
$(function () {
    guncelle();
    const ulke = localStorage.getItem("ulke") || "hepsi";
    const kategori = localStorage.getItem("kategori") || "hepsi";
    const gelecek = localStorage.getItem("gelecek") || "kapali";

    if(ulke !== "hepsi"){
        $(".ulkeSec li").each(function(){
            if($(this).data("ulke") === ulke){
                var cur = $(this).find("div p").html();
                $(this).parent().parent().find(".default_option li div p span").html(cur);
            }
        });
    }

    if(kategori !== "hepsi"){
        $(".kategoriSec li").each(function(){
            if($(this).data("kategori") === kategori){
                var cur = $(this).find("div p").html();
                $(this).parent().parent().find(".default_option li div p span").html(cur);
            }
        });
    }

    if(gelecek === "acik"){
        $(".check_option").parent().toggleClass("active").toggleClass("soldur");
    }

});