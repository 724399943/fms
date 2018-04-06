<extend name="Wap/fms/Common:base" />
<block name="main">
	<div class="main pd">
        <div class="actdet">
            <p class="title">{$info['title']}</p>   
            <p class="pt"><span>发布者:{$info['author']}</span><span>浏览次数:{$info['hits']}</span><span>时间:{$info['add_time']|date="Y/m/d",###}</span></p>  
            <div class="txt">
                {$info['content']}
            </div>
        </div>
    </div>
</block>