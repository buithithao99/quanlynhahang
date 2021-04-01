$(function() {
    $('#side-menu').metisMenu();
});
//Loads the correct sidebar on window load,
//collapses the sidebar on window resize.
// Sets the min-height of #page-wrapper to window size
$(function() {
    $(window).bind("load resize", function() {
        topOffset = 50;
        width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
        if (width < 768) {
            $('div.navbar-collapse').addClass('collapse');
            topOffset = 100; // 2-row-menu
        } else {
            $('div.navbar-collapse').removeClass('collapse');
        }

        height = ((this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height) - 1;
        height = height - topOffset;
        if (height < 1) height = 1;
        if (height > topOffset) {
            $("#page-wrapper").css("min-height", (height) + "px");
        }
    });
});

$(document).ready(function(){
    $('#menu-table').DataTable({
        responsive: true
    });
    $('#city').change(function(e){
        var cityId = $(this).val();
        $.ajax({
            url: "/district",
            type: 'POST',
            data: {
                cityId:cityId
            },
            beforeSend: function(xhr) {
              xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            }
        }).done(function(res){
              console.log(res);
              $('#district').html(res);
        });
    });
    $('#district').change(function(e){
        var districtId = $(this).val();
        $.ajax({
            url: "/commune",
            type: 'POST',
            data: {
                districtId:districtId
            },
            beforeSend: function(xhr) {
              xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            }
        }).done(function(res){
              $('#commune').html(res);
        });
    });
    $("#category_id").on('change',function(){
        if(this.value === "3" || this.value === "4"){
            $(".regions").css("display","none");
        }else{
            $(".regions").css("display","block");
        }
    });
});

var closebtns = document.getElementsByClassName("close");
var i;

for (i = 0; i < closebtns.length; i++) {
  closebtns[i].addEventListener("click", function() {
    this.parentElement.style.display = 'none';
  });
}