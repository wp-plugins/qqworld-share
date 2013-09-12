function qqworld_plugin_share() {
	var $ = jQuery;
	var root = '.qqworld-share-container ';
	var action = function() {
		var source = qqworld_share.source;
		var source_url = qqworld_share.source_url;
		var title = qqworld_share.title;
		var summary = qqworld_share.summary;
		var url = qqworld_share.url;
		var pic = typeof qqworld_share.pic == 'string' ? qqworld_share.pic : qqworld_share.pic.join('|');
		$(document).on('click', root + '.more', function() {
			$(this).fadeOut('fast').parent().parent().css({height: 'auto', padding: '50px 0 0 0'});
		}).on('click', root + '.weibo', function() {
			var pic = typeof qqworld_share.pic == 'string' ? qqworld_share.pic : qqworld_share.pic.join('||');
			window.open('http://service.weibo.com/share/share.php?title=' + title + ' - ' + summary + '&url=' + url + '&source=' + source + '&appkey=&pic=' + pic + '&ralateUid=', '_blank', 'width=615,height=550');
		}).on('click', root + '.qzone', function() {			
			window.open('http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=' + url + '&title=' + title + '&pics=' + pic + '&summary=' + summary, '_blank', 'width=615,height=550');
		}).on('click', root + '.qq', function() {			
			window.open('http://connect.qq.com/widget/shareqq/index.html?url=' + url + '&showcount=0&desc=' + title + '&summary=' + summary + '&title=' + title + '&site=&pics=' + pic, '_blank', 'width=768,height=600');
		}).on('click', root + '.facebook', function() {
			window.open('http://www.facebook.com/sharer.php?u=' + url + '&t=' + title, '_blank', 'width=615,height=350');
		}).on('click', root + '.twitter', function() {
			window.open('https://twitter.com/intent/tweet?source=webclient&text=' + title + ' - ' + summary + ' / ' + url, '_blank', 'width=615,height=450');
		}).on('click', root + '.linkedin', function() {
			window.open('http://www.linkedin.com/shareArticle?mini=true&ro=true&url=' + url + '&title=' + title + '&summary=' + summary + '&source=' + source, '_blank', 'width=615,height=450');
		}).on('click', root + '.google', function() {
			window.open('https://plus.google.com/share?url=' + url + '&t=' + title, '_blank', 'width=500,height=510');
		}).on('click', root + '.jianghu-taobao', function() {
			window.open('http://share.jianghu.taobao.com/share/addShare.htm?url=' + url + '&title=' + title, '_blank', 'width=615,height=450');
		}).on('click', root + '.sohu-t', function() {
			var pic = typeof qqworld_share.pic == 'string' ? qqworld_share.pic : qqworld_share.pic.join(',');
			window.open('http://t.sohu.com/third/post.jsp?url=' + url + '&title=' + title + '&content=' + summary + '&pic=' + pic, '_blank', 'width=615,height=450');
		}).on('click', root + '.pengyou', function() {
			window.open('http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?to=pengyou&url=' + url + '&pics=' + pic + '&title=' + title + '&summary=' + summary, '_blank', 'width=615,height=450');
		}).on('click', root + '.tencent_weibo', function() {
			window.open('http://share.v.t.qq.com/index.php?c=share&a=index&title=' + title + '&url=' + url + '&appkey=&site=' + url + '&pic=' + pic, '_blank', 'width=615,height=450');
		}).on('click', root + '.douban', function() {
			var pic = typeof qqworld_share.pic == 'string' ? qqworld_share.pic : qqworld_share.pic[0];
			window.open('http://shuo.douban.com/!service/share?image=' + pic + '&href=' + url + '&name=' + title + '&text=' + summary, '_blank', 'width=615,height=450');
		}).on('click', root + '.kaixin001', function() {
			var pic = typeof qqworld_share.pic == 'string' ? qqworld_share.pic : qqworld_share.pic.join(',');
			window.open('http://www.kaixin001.com/rest/records.php?content=' + summary + '&url=' + url + '&starid=&aid=&style=11&pic=' + pic + '&t=76', '_blank', 'width=615,height=450');
		}).on('click', root + '.renren', function() {
			var pic = typeof qqworld_share.pic == 'string' ? qqworld_share.pic : qqworld_share.pic[0];
			window.open('http://widget.renren.com/dialog/share?resourceUrl=' + source_url + '&srcUrl=' + url + '&title=' + title + '&pic=' + pic + '&description=' + summary, '_blank', 'width=615,height=600');
		}).on('click', root + '.t-163', function() {
			var pic = typeof qqworld_share.pic == 'string' ? qqworld_share.pic : qqworld_share.pic.join(',');
			window.open('http://t.163.com/article/user/checkLogin.do?source=' + source + '&info=' + title + ' - ' + summary + '&images=' + pic, '_blank', 'width=615,height=600');
		}).on('click', root + '.baidu', function() {
			window.open('http://hi.baidu.com/pub/show/share?url=' + url + '&title=' + title, '_blank', 'width=615,height=600');
		}).on('click', root + '.tianya', function() {
			var pic = typeof qqworld_share.pic == 'string' ? qqworld_share.pic : qqworld_share.pic.join(',');
			window.open('http://open.tianya.cn/widget/send_for.php?action=send-html&shareTo=1&appkey=&title=' + title + '&url=' + url + '&picUrl=' + pic, '_blank', 'width=615,height=640');
		});
	}
	action();
}
qqworld_plugin_share();