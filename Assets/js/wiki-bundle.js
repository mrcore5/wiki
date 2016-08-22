/**
 * Slugify a url
 */
function slugify(me, slugFormId)
{
	$('#' + slugFormId).val(
		me.value
			.toLowerCase().trim()
			.replace(/ |_|\/|\\/g,'-')    // space_/\ to -
			.replace(/[^\w-]+/g,'') // non alpha-numeric
			.replace(/-+/g,'-')     // multiple -
			.replace(/-$/g,'')      // trailing -
			.replace(/^-/g,'')      // leading -
	);
}

/**
 * Hashtagify a string
 */
function hashtagify(me, hashFormId)
{
	$('#' + hashFormId).val(
		me.value
			.toLowerCase().trim()
			.replace(/ |_/g,'')
			.replace(/[^\w-]+/g,'')
			.replace(/-+/g,'')
			.replace(/-$/g,'')
			.replace(/^-/g,'')
	);
}

/**
 * Timer countdown function
 */
function countdown(options) {
	var timer,
	instance = this,
	seconds = options.seconds || 10,
	updateStatus = options.onUpdateStatus || function () {},
	counterEnd = options.onCounterEnd || function () {};

	function decrementCounter() {
		updateStatus(seconds);
		if (seconds === 0) {
			counterEnd();
			instance.stop();
		}
		seconds--;
	}

	this.start = function () {
		clearInterval(timer);
		timer = 0;
		seconds = options.seconds;
		timer = setInterval(decrementCounter, 1000);
	};

	this.stop = function () {
		clearInterval(timer);
	};
}

/*
 function urldecode(url)
 URL decode %xx and +
 mReschke 2012-10-26
*/
function urldecode(url) {
  return decodeURIComponent(url.replace(/\+/g, ' '));
}

/*
 function urlencode(url)
 URL encode %xx and +
 mReschke 2012-10-26
*/
function urlencode(url) {
  return encodeURIComponent(url).replace(/%20/g, '+');
}

/*
 function toggle_wiki_header(id)
 Collapse/Expand this Text_Wiki header content div
 mReschke 2011-04-12
*/
function toggle_wiki_header(id) {
	div = document.getElementById(id+"__content");
	link = document.getElementById(id+"__link");
	if (div.style.display == "none") {
		div.style.display = "";
		link.innerHTML = "[-]";
	} else {
		div.style.display = "none";
		link.innerHTML = "[+]";
	}
	//adjust_large_width();
}

/*
 function toggle_wiki_headers(collapse)
 Collapse/Expand ALL Text_Wiki Headers and alter their +/- link status
 mReschke 2011-04-13
*/
function toggle_wiki_headers(collapse) {
	//Toggle Header Content Div Display
	var divs = document.getElementsByTagName("div");
	$("div[id*='__content']").each(function() {
		if (collapse) {
			this.style.display = 'none';
		} else {
			this.style.display = 'block';
		}
	});

	//Toggle Header [+]/[-] link display
	$("a[id*='__link']").each(function() {
		console.log(this.id);
		if (collapse) {
			this.innerHTML = '[+]';
		} else {
			this.innerHTML = '[-]';
		}
	});

}

/*
 resize_object(obj)
 used for <embed url>
 mReschke 2013-05-03
*/
function resize_embed_url(obj) {
	var height = document.documentElement.clientHeight;
	height -= document.getElementById(obj).offsetTop;
	height -= 110;
	if (height < 600) height = 800;
	document.getElementById(obj).style.height = height +"px";
}

//# sourceMappingURL=wiki-bundle.js.map
