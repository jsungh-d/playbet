<style type="text/css">
    .dataTables_scrollHeadInner {width: auto !important;}
    .dataTables_scrollBody {margin-bottom: 6px;}
</style>

<!-- page content -->
<div class="right_col" role="main">
    <div class="page-title">
        <h3>메트릭스(일반) <small>종목별 분석된 키워드를 관리합니다.(키워드메트릭스)</small></h3>
    </div>

    <div class="x_panel">
        <div class="x_content">
            <br />
            <div class="form-horizontal form-label-left">
                <div class="form-group">
                    <div class="form-inline">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">시점</label>
                        <div class="input-group date" data-provide="datepicker" data-date-format="yyyy-mm-dd">
                            <div class="input-group-addon">
                                <span class="fa fa-calendar"></span>
                            </div>
                            <input type="text" class="form-control" id="sdate" value="" placeholder="날짜지정">
                        </div>
                        <div class="input-group date" data-provide="datepicker" data-date-format="yyyy-mm-dd">
                            <div class="input-group-addon">
                                <span class="fa fa-calendar"></span>
                            </div>
                            <input type="text" class="form-control" id="edate" value="" placeholder="날짜지정">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-inline">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">키워드</label>

                        <select id="type" class="form-control">
                            <option value="">전체</option>
                            <option value="Y">유가</option>
                            <option value="K">코스닥</option>
                            <option value="N">코넥스</option>
                            <option value="E">기타</option>
                        </select>

                        <div class="input-group">
                            <input type="text" id="search" class="form-control optionSearchText" placeholder="">
                            <span class="input-group-btn">
                                <button class="btn btn-primary" id="searchBtn" type="button">검색</button>
                            </span>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-2 custom_table_header">
            <div class="x_panel">
                <div class="x_content">
                    <table id="keyword_kospiTable1" class="table table-bordered bulk_action" style="margin-top: 0 !important;">
                        <thead>
                            <tr>
                                <th>키워드</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text_space">
                                    키워드명1
                                    <i class="fa fa-close"></i>
                                </td>
                            </tr>
                            <tr>
                                <td class="text_space">
                                    키워드명2
                                    <i class="fa fa-close"></i>
                                </td>
                            </tr>
                            <tr>
                                <td class="text_space">
                                    키워드명3
                                    <i class="fa fa-close"></i>
                                </td>
                            </tr>
                            <tr>
                                <td class="text_space">
                                    키워드명4
                                    <i class="fa fa-close"></i>
                                </td>
                            </tr>
                            <tr>
                                <td class="text_space">
                                    키워드명5
                                    <i class="fa fa-close"></i>
                                </td>
                            </tr>
                            <tr>
                                <td class="text_space">
                                    키워드명6
                                    <i class="fa fa-close"></i>
                                </td>
                            </tr>
                            <tr>
                                <td class="text_space">
                                    키워드명7
                                    <i class="fa fa-close"></i>
                                </td>
                            </tr>
                            <tr>
                                <td class="text_space">
                                    키워드명8
                                    <i class="fa fa-close"></i>
                                </td>
                            </tr>
                            <tr>
                                <td class="text_space">
                                    키워드명9
                                    <i class="fa fa-close"></i>
                                </td>
                            </tr>

                        </tbody>
                    </table>

                    <!--삭제모달 -->
                    <div id="removeModal" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">삭제 경고</h4>
                                </div>
                                <div class="modal-body">
                                    <p>
                                        선택하신 키워드를 삭제 하시겠습니까?
                                        <input type="hidden" id="idx" value="">
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default antoclose" data-dismiss="modal">닫기</button>
                                    <button type="button" class="btn btn-primary antosubmit" id="expoSubmit">삭제처리</button>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>

        <script type="text/javascript">
            $(document).ready(function () {
                var table = $('#keyword_kospiTable1').DataTable();

                $('#keyword_kospiTable1 tbody').on('click', 'i.fa-close', function () {
                    $('#removeModal').modal('show');
                    table.row($(this).parents('tr')).remove().draw();
                });


                //         var table1 = $('#keyword_kospiTable1').dataTable({
                //     scrollY: "600px",
                //     scrollCollapse: true,
                //     pagingType: "simple",
                //     dom: '<"datatable_header"f>t<"datatable_footer"p>',
                //     ajax: '/index.php/dataFunction/keywordStockLists',
                //     pageLength: 50,
                //     columns: [
                //         {data: "company_name_i"}
                //     ],
                //     order: [],
                //     columnDefs: [{
                //             orderable: true,
                //             className: 'cursor',
                //             targets: 0
                //         }],
                //     language: {
                //         lengthMenu: "_MENU_",
                //         search: "",
                //         paginate: {
                //             "next": "&raquo;",
                //             "previous": "&laquo;"
                //         }
                //     }
                // });
            });
        </script>

        <div class="col-xs-4 custom_table_header">
            <div class="x_panel">
                <div class="x_content">
                    <table id="keyword_kospiTable2" class="table table-bordered bulk_action" style="margin-top: 0 !important;">
                        <colgroup><col width="30px"><col width="*"></colgroup>
                        <thead>
                            <tr>
                                <th style="font-size:11px; height: 34px; padding-top:0;padding-bottom: 0px;vertical-align: middle"><input type="checkbox" id="check-all1"></th>
                                <th style="font-size:11px; height: 34px; padding-top:0;padding-bottom: 0px;vertical-align: middle">종목 분석 <br>키워드</th>
                                <th style="font-size:11px; height: 34px; padding-top:0;padding-bottom: 0px;vertical-align: middle">뉴스 건수 <br>(종목)</th>
                                <th style="font-size:11px; height: 34px; padding-top:0;padding-bottom: 0px;vertical-align: middle">언급 량 <br>(종목)</th>
                                <th style="font-size:11px; height: 34px; padding-top:0;padding-bottom: 0px;vertical-align: middle">언급 량 <br>(전체)</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>


                    <!-- 노출모달 -->
                    <div id="allExpoModal" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">노출 설정</h4>
                                </div>
                                <div class="modal-body">
                                    <p>
                                        선택하신 키워드를 노출하시겠습니까?
                                        <input type="hidden" id="expo_keyword" value="">
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default antoclose" data-dismiss="modal">닫기</button>
                                    <button type="button" class="btn btn-primary antosubmit" id="expoSubmit">처리</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 비노출모달 -->
                    <div id="allUnexpoModal" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">비노출 설정</h4>
                                </div>
                                <div class="modal-body">
                                    <p>
                                        선택하신 키워드를 비노출 처리하겠습니까?
                                        <input type="hidden" id="unexpo_keyword" value="">
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default antoclose" data-dismiss="modal">닫기</button>
                                    <button type="button" class="btn btn-primary antosubmit" id="unexpoSubmit">처리</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--완전삭제모달-->
                    <div id="kospiAllDelModal" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">삭제경고</h4>
                                </div>
                                <div class="modal-body">
                                    <p>
                                        전체 삭제하시겠습니까?
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default antoclose" data-dismiss="modal">닫기</button>
                                    <button type="button" class="btn btn-primary antosubmit">삭제처리</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--키워드삭제모달-->
                    <div id="kospiDelModal" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">삭제경고</h4>
                                </div>
                                <div class="modal-body">
                                    <p>
                                        키워드를 삭제하시겠습니까?
                                    </p>
                                    <input type="checkbox" id="delAllCheckBox" style="margin-top: 20px;">전체삭제
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default antoclose" data-dismiss="modal">닫기</button>
                                    <button type="button" id="delNewKeyWord" class="btn btn-primary antosubmit">삭제처리</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--동의어연결모달-->
                    <div id="kospiConnectModal" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">동의어 연결</h4>
                                </div>
                                <div class="modal-body">
                                    '<span id="connect_title">이재</span>'를
                                    <input type="text" id="synonym1" class="form-control" style="width:40%; display: inline-block;">
                                    연결하시겠습니까?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default antoclose" data-dismiss="modal">닫기</button>
                                    <button type="button" class="btn btn-primary antosubmit" id="connect_btn1" value="">연결</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--동의어연결모달 연결1-->
                    <div id="kospiConnectModal2" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">동의어 연결</h4>
                                </div>
                                <div class="modal-body">
                                    <p>
                                        <span id="connect_title2">이제</span>를 <span id="connect_title2_1">이재용</span> 단어에 연결하였습니다.
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default antoclose connect_btn">닫기</button>
                                    <button type="button" class="btn btn-primary antosubmit connect_btn">확인</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--동의어연결모달 연결2-->
                    <div id="kospiConnectModal2_1" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">동의어 연결</h4>
                                </div>
                                <div class="modal-body">
                                    <p>
                                        <span id="connect_title2_11">이재</span>는 이미 <span id="connect_title2_12">이재용</span>으로 연결되어있습니다.
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default antoclose" data-dismiss="modal">닫기</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--동의어연결모달 연결3-1-->
                    <div id="kospiConnectModal3" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">동의어 연결</h4>
                                </div>
                                <div class="modal-body">
                                    <p>
                                        "<span id="connect_title3">이재용</span>" 단어가 등록 되 있지 않습니다. 새로운 단어로 등록하시겠습니까?
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default antoclose" data-dismiss="modal">닫기</button>
                                    <button type="button" class="btn btn-primary antosubmit" id="connect_btn3">등록</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--동의어연결모달 연결3-2-->
                    <div id="kospiConnectModal4" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">동의어 연결</h4>
                                </div>
                                <div class="modal-body">
                                    <p>
                                        "<span id="connect_title4">이재용</span>" 단어를 등록하고 <span id="connect_title4_1">이재</span>를 <span id="connect_title4_2">이재용</span> 단어에 연결하였습니다.
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default antoclose connect_btn">닫기</button>
                                    <button type="button" class="btn btn-primary antosubmit connect_btn">확인</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xs-3 custom_table_header">
            <div class="x_panel">
                <div class="x_content">
                    <table id="keyword_kospiTable3" class="table table-bordered bulk_action">
                        <colgroup><col width="30px"><col width="*"></colgroup>
                        <thead>
                            <tr>
                                <th>번호</th>
                                <th><input type="checkbox" id="check-all2"></th>
                                <th>신규 노출 키워드</th>
                                <th>색상</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="addNewKeyWordModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">노출키워드</h4>
                    </div>
                    <div class="modal-body">
                        <input type="text" id="addNewKeyWord" class="form-control" style="width:40%; display: inline-block;">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default antoclose" data-dismiss="modal">취소</button>
                        <button type="button" id="addNewKeyWordBtn" class="btn btn-primary antosubmit">추가</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xs-3 custom_table_header">
            <div class="x_panel">
                <div class="x_content">
                    <table id="keyword_kospiTable4" class="table table-bordered bulk_action">
                        <colgroup><col width="30px"><col width="*"></colgroup>
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="check-all3"></th>
                                <th>현재 노출 키워드</th>
                                <th>색상</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="stock_seq" value="">
    <input type="hidden" id="crp_cd" value="">
</div>

