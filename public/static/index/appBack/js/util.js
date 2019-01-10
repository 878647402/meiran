var util = {
	options: {
		ACTIVE_SRC1: "../images/home_r.png",
		NORMAL_SRC1: "images/home.png",
		ACTIVE_SRC2: "images/store_r.png",
		NORMAL_SRC2: "images/store.png",
		ACTIVE_SRC3: "../images/donations_r.png",
		NORMAL_SRC3: "../images/donations.png",
		ACTIVE_SRC4: "../images/mine_r.png",
		NORMAL_SRC4: "../images/mine.png",
// 		ACTIVE_COLOR: "#007aff",
// 		NORMAL_COLOR: "#000",
		subpages: ["html/tab-webview-subpage-chat.html", "html/tab-webview-subpage-contact.html"]
	},
	/**
	 *  简单封装了绘制原生view控件的方法
	 *  绘制内容支持font（文本，字体图标）,图片img , 矩形区域rect
	 */
	drawNative: function(id, styles, tags) {
		var view = new plus.nativeObj.View(id, styles, tags);
		return view;
	},
	/**
	 * 初始化首个tab窗口 和 创建子webview窗口 
	 */
	initSubpage: function(aniShow) {
		var subpage_style = {
				top: 0,
				bottom: 51
			},
			subpages = util.options.subpages,
			self = plus.webview.currentWebview(),
			temp = {};
			
		//兼容安卓上添加titleNView 和 设置沉浸式模式会遮盖子webview内容
		if(mui.os.android) {
			if(plus.navigator.isImmersedStatusbar()) {
				subpage_style.top += plus.navigator.getStatusbarHeight();
			}
			if(self.getTitleNView()) {
				subpage_style.top += 40;
			}
			
		}

		// 初始化第一个tab项为首次显示
		temp[self.id] = "true";
		mui.extend(aniShow, temp);
		// 初始化绘制首个tab按钮
		util.toggleNview(0);

		for(var i = 0, len = subpages.length; i < len; i++) {

			if(!plus.webview.getWebviewById(subpages[i])) {
				var sub = plus.webview.create(subpages[i], subpages[i], subpage_style);
				//初始化隐藏
				sub.hide();
				// append到当前父webview
				self.append(sub);
			}
		}
	},
	/**	
	 * 点击切换tab窗口 
	 */
	changeSubpage: function(targetPage, activePage, aniShow) {
		//若为iOS平台或非首次显示，则直接显示
		if(mui.os.ios || aniShow[targetPage]) {
			plus.webview.show(targetPage);
		} else {
			//否则，使用fade-in动画，且保存变量
			var temp = {};
			temp[targetPage] = "true";
			mui.extend(aniShow, temp);
			plus.webview.show(targetPage, "fade-in", 300);
		}
		//隐藏当前 除了第一个父窗口
		if(activePage !== plus.webview.getLaunchWebview()) {
			plus.webview.hide(activePage);
		}
	},
	/**
	 * 点击重绘底部tab （view控件）
	 */
	toggleNview: function(currIndex) {
			switch(currIndex){
			case 0 :
				util.updateSubNView(0, util.options.ACTIVE_SRC1);
				util.updateSubNView(1, util.options.NORMAL_SRC2);
				util.updateSubNView(2, util.options.NORMAL_SRC3);
				util.updateSubNView(3, util.options.NORMAL_SRC4);
				break;
			case 1 :
				util.updateSubNView(0, util.options.NORMAL_SRC1);
				util.updateSubNView(1, util.options.ACTIVE_SRC2);
				util.updateSubNView(2, util.options.NORMAL_SRC3);
				util.updateSubNView(3, util.options.NORMAL_SRC4);
				break;
			case 2 :
				util.updateSubNView(0, util.options.NORMAL_SRC1);
				util.updateSubNView(1, util.options.NORMAL_SRC2);
				util.updateSubNView(2, util.options.ACTIVE_SRC3);
				util.updateSubNView(3, util.options.NORMAL_SRC4);
				break;
			case 3 :
				util.updateSubNView(0, util.options.NORMAL_SRC1);
				util.updateSubNView(1, util.options.NORMAL_SRC2);
				util.updateSubNView(2, util.options.NORMAL_SRC3);
				util.updateSubNView(3, util.options.ACTIVE_SRC4);
				break;
		}
	},
	/*
	 * 改变颜色
	 */
// 	changeColor: function(obj, color) {
// 		obj.color = color;
// 		return obj;
// 	},
	/*
	 * 利用 plus.nativeObj.View 提供的 drawText 方法更新 view 控件
	 */
	updateSubNView: function(currIndex, src) {
		var self = plus.webview.currentWebview(),
			nviewEvent = plus.nativeObj.View.getViewById("tabBar"), // 获取nview控件对象
			nviewObj = self.getStyle().subNViews[0], // 获取nview对象的属性
			currTag = nviewObj.tags[currIndex]; // 获取当前需重绘的tag

		nviewEvent.drawBitmap(src,'', currTag.position,currTag.id);
	}
};