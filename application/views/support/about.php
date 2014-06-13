<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <title>关于我们 | 软件学院学生管理系统</title>
    <meta charset='utf-8' />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="谢浩哲">
    <!-- Page Icon -->
    <link rel="shortcut icon" href="http://rjxy.hfut.edu.cn/cms/wp-content/uploads/system-reserved/favicon.png" />
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap-responsive.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/support/style.css">
    <!-- Java Script -->
    <script src="<?php echo base_url(); ?>assets/js/jquery-1.11.0.min.js"></script> 
    <script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script> 
    <!--For IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
            <script src="http://cdn.bootcss.com/html5shiv/3.7.0/html5shiv.min.js"></script>
    <![endif]-->
</head>

<body data-spy="scroll" data-target="#sidebar">
    <div id="header" class="row-fluid">
        <div id="logo" class="span4">
            <a href="<?php echo base_url(); ?>"><img id="logo" src="<?php echo base_url(); ?>assets/img/product-logo.png" alt="软件学院学生管理系统" /></a>
        </div> <!-- #logo -->
        <?php if ( !isset($profile) ): ?>
            <div id="quick-login" class="offset4 span4">
                <form class="form-inline" action="http://rjxy.hfut.edu.cn/stumgr/accounts/signin" method="post" accept-charset="utf-8">
                    <input id="username" name="username" type="text" class="input-small" placeholder="用户名" maxlength="16">
                    <input id="password" name="password" type="password" class="input-small" placeholder="密码" maxlength="16">
                    <label id="remember" class="checkbox">
                        <input type="checkbox" id="persistent-cookie" name="persistent-cookie"> 保持登录状态
                    </label>
                    <button type="submit" class="btn btn-primary">登录</button>
                </form>
            </div> <!-- #quick-login -->
            <script type="text/javascript" src="<?php echo base_url().'assets/js/placeholder.min.js'; ?>"></script>
            <script type="text/javascript">$('input, textarea').placeholder();</script>
        <?php endif; ?>
    </div> <!-- #header -->
    <div class="container">
        <div class="row-fluid">
            <div id="sidebar" class="span3">
                <ul id="sidenav" class="nav nav-list sidenav">
                    <li class="active"><a href="#developer"><i class="icon-chevron-right"></i> 开发人员</a></li>
                    <li><a href="#license"><i class="icon-chevron-right"></i> 许可协议</a></li>
                    <li><a href="#changelog"><i class="icon-chevron-right"></i> 更新日志</a></li>
                </ul>
            </div> <!-- #sidebar -->
            <div class="span9">
                <section id="developer">
                    <div class="page-header">
                        <h1>开发人员</h1>
                    </div>
                    <p><strong>本软件由如下志愿者提供技术支持:</strong></p>
                    <ul class="supporter">
                        <li>谢浩哲 &lt;<a href="mailto:zjhzxhz@gmail.com">zjhzxhz@gmail.com</a>&gt;</li>
                    </ul>
                    <p><strong>特别致谢:</strong></p>
                    <ul class="supporter">
                        <li>金柳颀 &lt;<a href="mailto:kinuxroot@163.com">kinuxroot@163.com</a>&gt;</li>
                        <li>靳昌&nbsp;&nbsp;&nbsp;&nbsp;&lt;<a href="mailto:amosjin45@gmail.com">amosjin45@gmail.com</a>&gt;</li>
                        <li>李翀&nbsp;&nbsp;&nbsp;&nbsp;&lt;<a href="mailto:aresherochong@gmail.com">aresherochong@gmail.com</a>&gt;</li>
                        <li>华心童 &lt;<a href="mailto:ideal19920402@gmail.com">ideal19920402@gmail.com</a>&gt;</li>
                    </ul>
                </section> <!-- #developer -->
                <section id="license">
                    <div class="page-header">
                        <h1>许可协议</h1>
                    </div>
                    <p><strong>本软件授权给: </strong>合肥工业大学软件学院</p>
                    <p>本软件使用与发行遵循 <a href="http://www.gnu.org/licenses/gpl.html">通用公共许可(GPL)</a> 协议.</p>
                </section> <!-- #license -->
                <section id="changelog">
                    <div class="page-header">
                        <h1>更新日志</h1>
                    </div>
                    <div id="version-2.0">
                        <h3>
                            V 2.2 <small>(2014年08月31日)</small>
                        </h3>
                        <ul>
                            <li>重写了60%的前端代码</li>
                            <li>完全兼容IE7浏览器</li>
                            <li>全新的响应式设计, 增强了移动终端的用户体验</li>
                        </ul>
                    </div> <!-- #version-2.1 -->
                    <div id="version-2.0">
                        <h3>
                            V 2.1 <small>(2013年09月30日)</small>
                        </h3>
                        <ul>
                            <li>修改部分UI细节</li>
                            <li>完全兼容IE8浏览器</li>
                            <li>使用Messenger.js进行消息提醒</li>
                        </ul>
                    </div> <!-- #version-2.1 -->
                    <div id="version-2.0">
                        <h3>
                            V 2.0 <small>(2013年08月30日)</small>
                        </h3>
                        <ul>
                            <li>使用BootStrap重新设计前端框架</li>
                            <li>重构了全部代码</li>
                            <li>优化了数据库结构</li>
                        </ul>
                    </div> <!-- #version-2.0 -->
                    <div id="version-1.0">
                        <h3>
                            V 1.0 <small>(2013年06月30日)</small>
                        </h3>
                        <ul>
                            <li>首次在软件学院使用该软件</li>
                        </ul>
                    </div> <!-- #version-1.0 -->
                    <p><strong>Follow us on GitHub: </strong><a href="https://github.com/zjhzxhz/stumgr">GitHub@stumgr</a></p>
                </section> <!-- #changelog -->
            </div>
        </div>
    </div> <!-- #container -->
    <div id="footer">
        Copyright&copy; 2009-<?php echo date('Y'); ?> <a href="http://rjxy.hfut.edu.cn" target="_blank">School of Software in HFUT</a>. All rights reserved.
    </div> <!-- #footer -->
    <!-- Javascript -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script type="text/javascript">
        $('section [href^=#]').click(function (e) {
            e.preventDefault()
        });
    </script>
    <script type="text/javascript">
        setTimeout(function () {
            $('#sidenav').affix({
                offset: {
                    top: function () { 
                        return $(window).width() <= 780 ? 240 : 160 
                    }, bottom: 270
                }
            })
        }, 100);
    </script>
</body>
</html>
