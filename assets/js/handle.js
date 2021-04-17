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
    $('#category_id').change(function(e){
        var cateId = $(this).val();
        $.ajax({
            url: "/showproduct",
            type: 'POST',
            data: {
                cateId:cateId
            },
            beforeSend: function(xhr) {
              xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            }
        }).done(function(res){
              $('#product_id').html(res);
        });
    });
    $('#product_id').change(function(e){
        var proId = $(this).val();
        $.ajax({
            url: "/showprice",
            type: 'POST',
            data: {
                proId:proId
            },
            beforeSend: function(xhr) {
              xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            }
        }).done(function(res){
            document.getElementById("price").setAttribute("value", res);
        });
    });
    $('#quantity').change(function(e){
        var qty = $(this).val();
        var price = $("#price").val();
        document.getElementById("total").setAttribute("value", qty*price);
    });
    $("#form-filter").hide();
    $('#revenue').change(function(e){
        var option = $(this).val();
        if(option === 'day'){
            $("#form-filter").hide();
            $("#chart_month").hide();
            $("#chart_year").hide();
            $("#chart_day").show();
        }else if(option === 'month'){
            $("#form-filter").hide();
            $("#chart_day").hide();
            $("#chart_year").hide();
            $("#chart_month").show();
        }else if(option === 'year'){
            $("#form-filter").hide();
            $("#chart_month").hide();
            $("#chart_day").hide();
            $("#chart_year").show();
        }else{
            $("#form-filter").show();
            $("#chart_month").hide();
            $("#chart_day").hide();
            $("#chart_year").hide();
        }
    });
    $(document).on('click', '#filter', function() { 
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        if(from_date != '' && to_date != ''){
            $.ajax({
                url: "/customfilter",
                type: 'POST',
                data: {
                    from_date:from_date,
                    to_date:to_date
                }
            }).done(function(res){
                var arr = [];
                 // Load the Visualization API and the corechart package.
                google.charts.load('current', {'packages':['corechart']});

                // Set a callback to run when the Google Visualization API is loaded.
                google.charts.setOnLoadCallback(drawChartCustom);
                function drawChartCustom() {

                    // Create the data table.
                    var data = new google.visualization.DataTable();
                    for(let x of JSON.parse(res)){
                        arr.push([x["DATE(created_at)"],parseInt(x["sum(total)"])]);
                    }
                    data.addColumn('string', 'Time');
                    data.addColumn('number', 'Total');
                    data.addRows(arr);
                    // Set chart options
                    var options = {'title':'Doanh thu từ '+from_date+" đến "+to_date,
                                    'width':600,
                                    'height':600};
        
                    // Instantiate and draw our chart, passing in some options.
                    var chart = new google.visualization.PieChart(document.getElementById('chart_custom'));
                    chart.draw(data, options);
                }
            });
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