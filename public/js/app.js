$(document).ready(function() {
    // all the variables
    var developermode = false;
    var wrapper = $(".wrapper");
    var wrapperbg = $(".wrapper-background");

    // aside toggle, hide and show programm start
    function asideToggle() {
        if(wrapper.hasClass('aside-close')) {
            wrapper.removeClass("aside-close");
        } else {
            wrapper.addClass("aside-close");
        }
    }
    function asideHide() {
        wrapper.addClass("aside-close");
    }
    function asideShow() {
        wrapper.removeClass("aside-close");
    }

    function layer() {
        var display = false;
        // get the window width
        var winwidth = $(window).outerWidth(true);
        console.log(winwidth);

        if(winwidth <= 992) {
            if(wrapperbg.hasClass('none')) {
                wrapperbg.removeClass('none');
                display = false;
            } else {
                wrapperbg.addClass('none');
                display = true;
            }
        }
        return display;
    }


    // click on toggle button (toggle)
    $("#aside-toggle").on("click", function(event) {
        asideToggle();
        layer();

        event.preventDefault();
    });


    // click on the wrapper-background (close)
    $(".wrapper-background").on("click", function() {
        asideHide();
        layer();
    });


    // click on the aside-close (close)
    $('a#aside-close').on("click", function() {
        asideHide();
        layer();
    });


    // on resize event aside toggle (toggle, optional)
    $(window).on("resize", function() {
        if(developermode) {
            asideToggle();
            layer();
        }
    });


    // dropdown toggle programm start
    $(".dropdown > a").on("click", function(event) {
        var parent = $(this).closest("li.dropdown");

        if(parent.hasClass("active")) {
            parent.removeClass("active");
        } else {
            parent.addClass("active");
            $( "li.dropdown" ).not( parent ).removeClass( "active" );
        }
        event.preventDefault();
    });

    // user dropdown toggle programm start
    $(".user-dropdown > a").on("click", function(event) {
        var parent = $(this).closest("li.user-dropdown");

        if(parent.hasClass("active")) {
            parent.removeClass("active");
        } else {
            parent.addClass("active");
            $( "li.user-dropdown" ).not( parent ).removeClass( "active" );
        }
        event.preventDefault();
    });

    $(document).click(function(event){
        var value = $(event.target).closest('.user-dropdown > a, .user-dropdown .sub-menu').length;
        if (value == 0) {
            if ($('.user-dropdown').hasClass('active')) {
                $('.user-dropdown').removeClass('active');
            }
        }
    });


    // rightbar script
  sidebarToggle('.settings','.right-bar', 'active');

	function sidebarToggle(clickElement, sidebarElement, sidebarClass, eventName='click') {
		$(clickElement).on(eventName, function(){
			var sidebar = $(sidebarElement);
			if (sidebar.hasClass(sidebarClass)) {
				sidebar.removeClass(sidebarClass);
			}else{
				sidebar.addClass(sidebarClass);
			}
		});

		$(document).on(eventName, function (event) {
			var value = $(event.target).closest(clickElement + ',' + sidebarElement).length;
			if (value == 0) {
				if ($(sidebarElement).hasClass(sidebarClass)) {
					$(sidebarElement).removeClass(sidebarClass);
				}
			}
		});
	}


    // list dropdown
    var menu = $('.wrapper').data('menu'),
        submenu = $('.wrapper').data('submenu');

    var li = '.aside-nav li#' + menu;

    $(li).addClass('active');
    $(li + ' ul li#' + submenu).addClass('active');


    // print media
    $('#print').click(function(){
        window.print();
    });


    // select checkbox
    $("#checkAll").click(function () {
        $('.delete-check input:checkbox').not(this).prop('checked', this.checked);

        if($('.list-header, .header-action-title, .header-action').hasClass('active')) {
            $('.list-header, .header-action-title, .header-action').removeClass("active");
        } else {
            $('.list-header, .header-action-title, .header-action').addClass("active");
        }
    });

    $('.list-table .delete-check input[type="checkbox"]').click(function (){
		var totalCheckbox = $(".list-table .delete-check input:checkbox").not('#checkAll').length;
		var check = $(".list-table .delete-check input:checkbox:checked").not('#checkAll').length;
        if(totalCheckbox == check){
            $('#checkAll').prop('checked', true);
        }else{
            $('#checkAll').prop('checked', false);
        }

        if(check > 0){
        	$('.list-header, .header-action-title, .header-action').addClass("active");
        }else{
        	$('.list-header, .header-action-title, .header-action').removeClass("active");
        }
    });

});
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

////////////////
// File Upload
//
function ekUpload() {
  function Init() {

    console.log("Upload Initialised");

    var fileSelect    = document.getElementById('file-upload'),
        fileDrag      = document.getElementById('file-drag'),
        submitButton  = document.getElementById('submit-button');

    fileSelect.addEventListener('change', fileSelectHandler, false);

    // Is XHR2 available?
    var xhr = new XMLHttpRequest();
    if (xhr.upload) {
      // File Drop
      fileDrag.addEventListener('dragover', fileDragHover, false);
      fileDrag.addEventListener('dragleave', fileDragHover, false);
      fileDrag.addEventListener('drop', fileSelectHandler, false);
    }
  }

  function fileDragHover(e) {
    var fileDrag = document.getElementById('file-drag');

    e.stopPropagation();
    e.preventDefault();

    fileDrag.className = (e.type === 'dragover' ? 'hover' : 'modal-body file-upload');
  }

  function fileSelectHandler(e) {
    // Fetch FileList object
    var files = e.target.files || e.dataTransfer.files;

    // Cancel event and hover styling
    fileDragHover(e);

    // Process all File objects
    for (var i = 0, f; f = files[i]; i++) {
      parseFile(f);
      uploadFile(f);
    }
  }

  // Output
  function output(msg) {
    // Response
    var m = document.getElementById('messages');
    m.innerHTML = msg;
  }

  function parseFile(file) {

    console.log(file.name);
    output(
      '<strong>' + encodeURI(file.name) + '</strong>'
    );

    // var fileType = file.type;
    // console.log(fileType);
    var imageName = file.name;

    var isGood = (/\.(?=gif|jpg|png|jpeg)/gi).test(imageName);
    if (isGood) {
      document.getElementById('start').classList.add("hidden");
      document.getElementById('response').classList.remove("hidden");
      document.getElementById('notimage').classList.add("hidden");
      // Thumbnail Preview
      document.getElementById('file-image').classList.remove("hidden");
      document.getElementById('file-image').src = URL.createObjectURL(file);
    }
    else {
      document.getElementById('file-image').classList.add("hidden");
      document.getElementById('notimage').classList.remove("hidden");
      document.getElementById('start').classList.remove("hidden");
      document.getElementById('response').classList.add("hidden");
      document.getElementById("file-upload-form").reset();
    }
  }

  function setProgressMaxValue(e) {
    var pBar = document.getElementById('file-progress');

    if (e.lengthComputable) {
      pBar.max = e.total;
    }
  }

  function updateFileProgress(e) {
    var pBar = document.getElementById('file-progress');

    if (e.lengthComputable) {
      pBar.value = e.loaded;
    }
  }

  function uploadFile(file) {

    var xhr = new XMLHttpRequest(),
      fileInput = document.getElementById('class-roster-file'),
      pBar = document.getElementById('file-progress'),
      fileSizeLimit = 1024; // In MB
    if (xhr.upload) {
      // Check if file is less than x MB
      if (file.size <= fileSizeLimit * 1024 * 1024) {
        // Progress bar
        pBar.style.display = 'inline';
        xhr.upload.addEventListener('loadstart', setProgressMaxValue, false);
        xhr.upload.addEventListener('progress', updateFileProgress, false);

        // File received / failed
        xhr.onreadystatechange = function(e) {
          if (xhr.readyState == 4) {
            // Everything is good!

            // progress.className = (xhr.status == 200 ? "success" : "failure");
            // document.location.reload(true);
          }
        };

        // Start upload
        xhr.open('POST', document.getElementById('file-upload-form').action, true);
        xhr.setRequestHeader('X-File-Name', file.name);
        xhr.setRequestHeader('X-File-Size', file.size);
        xhr.setRequestHeader('Content-Type', 'multipart/form-data');
        xhr.send(file);
      } else {
        output('Please upload a smaller file (< ' + fileSizeLimit + ' MB).');
      }
    }
  }

  // Check for the various File API support.
  if (window.File && window.FileList && window.FileReader) {
    Init();
  } else {
    document.getElementById('file-drag').style.display = 'none';
  }
}

// Printing the chalan invoice while clicking on chalan print button
function chalanPrint(){
    document.querySelector(".chalan-invoice").classList.remove("d-none");
    document.querySelector(".main-invoice").classList.add("d-none");
    window.print();
}

// Removing and adding the "d-none" on chalan print and main print while print has completed
window.addEventListener('afterprint', function () {
    document.querySelector(".chalan-invoice").classList.add("d-none");
    document.querySelector(".main-invoice").classList.remove("d-none");
});
