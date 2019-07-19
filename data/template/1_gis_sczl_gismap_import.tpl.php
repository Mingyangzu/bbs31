<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); ?>
<script src="https://code.jquery.com/jquery-3.1.1.min.js" type="text/javascript"></script>
<style>
    a[class="button-selectimg"]{color:#00A2D4;padding:4px 6px;border:1px solid #09C;border-radius:2px;text-decoration:none;}
    .input-file{margin:200px 300px;}
    input[id="avatval"]{padding:3px 6px;padding-left:10px;border:1px solid #E7EAEC;width:230px;height:25px;line-height:25px;border-left:3px solid #09C;background:#FAFAFB;border-radius:2px;}
    input[type='file']{border:0px;display:none;}
    .errormsg{min-height: 35px;line-height:35px;padding: 10px 20px;color: #F04700; }
    #subfile{border: 1px solid #09c;color: #09c;height: 25px;border-radius: 2px;}
</style>


<form action="" method="post" enctype="multipart/form-data" >
    <input type="text" id="avatval" placeholder="请选择文件···" readonly="readonly" style="vertical-align: middle;"/>
    <input type="file" name="upfiles" class="pn vm" id="avatar" required="required" />
    <a href="javascript:void(0);" class="button-selectimg" id="avatsel1">选择文件</a> 
    &nbsp;&nbsp;&nbsp;&nbsp;
    <button id="subfile" type="submit" ><em>导入信息</em></button>
</form>

<div class="errormsg"> <?php echo $response['msg'];?> </div>

<script type="text/javascript">
    $(function () {
        $("#avatsel1").click(function () {
            $("input[type='file']").trigger('click');
        });
        $("#avatval").click(function () {
            $("input[type='file']").trigger('click');
        });
        $("input[type='file']").change(function () {
            $("#avatval").val($(this).val());
        });
    });
</script>