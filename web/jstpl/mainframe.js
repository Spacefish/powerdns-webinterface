// This file was automatically generated from mainframe.soy.
// Please don't edit this file by hand.

goog.provide('neosolve.pdns');

goog.require('soy');
goog.require('soydata');


/**
 * @param {Object.<string, *>=} opt_data
 * @param {(null|undefined)=} opt_ignored
 * @return {string}
 * @notypecheck
 */
neosolve.pdns.mainFrame = function(opt_data, opt_ignored) {
  var output = '\t<div id="wrapper"><div id="header">PowerDNS Webinterface' + ((opt_data.userinfo) ? '<div class="logout_button">Logged in as ' + soy.$$escapeHtml(opt_data.userinfo.username) + '&nbsp;&nbsp;&nbsp;&nbsp;<a href="?p=login&a[0]=logout-logout"><img src="img/icons/cancel.png" /> Logout</a></div>' : '') + '</div><div id="main">';
  if (opt_data.menuItems) {
    output += '<div class="menu_left" id="menu"><ul>';
    var menuItemList19 = opt_data.menuItems;
    var menuItemListLen19 = menuItemList19.length;
    for (var menuItemIndex19 = 0; menuItemIndex19 < menuItemListLen19; menuItemIndex19++) {
      var menuItemData19 = menuItemList19[menuItemIndex19];
      output += '<li style="list-style-image:url(\'img/icons/' + soy.$$escapeHtml(menuItemData19.icon) + '.png\');"><a href="#">' + soy.$$escapeHtml(menuItemData19.caption) + '</a></li>';
    }
    output += '</ul></div>';
  }
  output += '<div class="' + ((opt_data.menuItems) ? 'contentbox_withmenu' : 'contentbox') + '"><div id="msg"></div><div id="content"></div><div class="copyleft">' + ((opt_data.userinfo) ? 'PowerDNS Webinterface ' + soy.$$escapeHtml(opt_data.appInfo.baseVersion) + '<br />' : '') + 'Copyright 2011-2013 Timo Witte Licensed under the Apache License (2.0)</div></div></div></div>';
  return output;
};
