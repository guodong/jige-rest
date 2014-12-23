数据表以及字段详细注释

【user】//user信息表
id:24位长度,mongodb的i全球唯一id
email:用户注册用的email
password:32位md5
tel:用户手机
realname:用户真实姓名
nickname:用户昵称
woid:微信openid，关联用户微信用
regtime:注册时间
role:用户角色/群组
is_verified：是否验证，主要用于认证用户
social:用户其它联系方式，比如QQ，微信等
campus_id：用户所在校区的id
config:用户配置信息，比如默认联系方式，默认交易地点等

【sellinfo】//交易信息表
id:24位长度,mongodb的i全球唯一id
bookid:出售的图书id/相当于商品id
sellerid:出售用户的id
status:订单状态。0代表未出售
price:出售价格
time:发布时间

【copartner】//商家信息表，用于其它客户端开发的登录信息
id:24位长度,mongodb的i全球唯一id
name:用户名
password:32位md5

【bookinfo】//图书信息表
id:24位长度,mongodb的i全球唯一id
bookstatus：图书状态，信息自动抓取后该标志位为未认证状态，工作人员人工审核后改成已认证
name:书名
author：作者
press:出版社
edition:版次
fixedprice:定价
imgpath:图书图片的url
discount:折扣，主要用于letsgo内部管理系统，和mallschool无关
isbn:ISBN
remark:工作人员审核时添加的备注信息
search:拼接了书名、作者、出版社、ISBN的字符串，用于图书检索
version:代表该图书信息来自豆瓣的哪个版本的接口，取值1或者2
doubanjson：从豆瓣抓取的图书信息的json文本