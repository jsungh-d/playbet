
<section id="post-data" class="main">

</section>

<div class="ajax-load text-center align_c" style="display:none">
    <p><img src="http://demo.itsolutionstuff.com/plugin/loader.gif">Loading More post</p>
</div>
</div>

<div class="black_wrapper">
    <div class="click_close"></div>
    <div class="black"></div>
</div>


<button type="button" class="write_icon" onclick="location.href = '/index/write'">
    <img src="/images/common/write.png" alt=""><br>
    <h5>내기 등록</h5>
</button>

<style type="text/css">

    .ajax-load{
        background: #e1e1e1;
        padding: 10px 0px;
        width: 100%;
    }
</style>

<script type="text/javascript">

    var page = 0;

    loadMoreData(page);

    $(window).scroll(function () {
        if ($(window).scrollTop() + $(window).height() >= $(document).height()) {
            page++;
            loadMoreData(page);
        }
    });

    function loadMoreData(page) {
        var category_idx = 'category_all';
        var search_keyword = $("#search_keyword").val();
        if (!$.trim(search_keyword)) {
            search_keyword = 'none';
        }
        var category_sub = '<?= $this->input->get('category_sub', true) ?>';

        $("#frame ul li.category").each(function (index) {
            if ($("#frame ul li.category:eq(" + index + ")").hasClass('nav_select_list')) {
                category_idx = $(this).attr('id');
            }
        });

        $.ajax({
            url: '/index.php/dataFunction/indexScroll?page=' + page + '&category=' + category_idx + '&text=' + search_keyword + '&category_sub=' + category_sub + '',
            type: "get",
            beforeSend: function () {
                $('.ajax-load').show();
            }
        }).done(function (data) {
            if (data == "") {
                $('.ajax-load').html("데이터가 없습니다.");
                return;
            }
            $('.ajax-load').hide();
            $("#post-data").append(data);

        }).fail(function (jqXHR, ajaxOptions, thrownError) {
            alert('서버 응답이 없습니다...');
        });
    }

    $(document).ready(function () {

        $(".betting_modal .cancle_btn").click(function () {
            $(".close-modal").trigger("click");
        });
    });
</script>



