

import tool from '@/utils/tool'

import Pusher from 'pusher-js' // import Pusher

class Messageio {
	pusher

	channel_private_user

	timer = null

	interval = 10 * 1000

	constructor() {

		this.pusher = new Push({
			url: process.env.VUE_APP_WS_URL, // websocket地址
			app_key: 'fef22e42b91aefd287acc2aded5234d0',
			auth: process.env.VUE_APP_WS_AUTH_URL // 订阅鉴权(仅限于私有频道)
		});
		console.log('已成功连接到消息服务器...')

	}

	getMessage() {
		// this.timer = setInterval(() => {
		// 	this.pusher.send({ event: 'get_unread_message' })
		// }, this.interval)
	}

	connection() {
		// this.pusher.connection()
		// 假设用户uid为1
		// var uid = 1;
		var userInfo = tool.getUserInfo()
		var uid = userInfo.user.id
		// 浏览器监听private-user-1私有频道的消息
		//需要订阅的频道信息
		this.channel_private_user = this.pusher.subscribe('private-user-' + uid);
		this.channel_private_user.trigger('client-message', {form_uid:2, content:"hello"});
		console.log("连接成功")
	}

}

export default Messageio



	// 建立连接
// 	var connection = new Push({
// 		url: 'ws://127.0.0.1:3131', // websocket地址
// 		app_key: '<app_key，在config/plugin/webman/push/app.php里获取>',
// 		auth: '/plugin/webman/push/auth' // 订阅鉴权(仅限于私有频道)
// 	});
// // 假设用户uid为1
// 	var uid = 1;
// // 浏览器监听user-1频道的消息，也就是用户uid为1的用户消息
// 	var user_channel = connection.subscribe('user-' + uid);
//
// // 当user-1频道有message事件的消息时
// 	user_channel.on('message', function(data) {
// 		// data里是消息内容
// 		console.log(data);
// 	});
// // 当user-1频道有friendApply事件时消息时
// 	user_channel.on('friendApply', function (data) {
// 		// data里是好友申请相关信息
// 		console.log(data);
// 	});

