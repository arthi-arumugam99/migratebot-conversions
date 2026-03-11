var clickables = document.querySelectorAll('[data-click]');
for (var i = 0; i < clickables.length; i++) {
  clickables[i].addEventListener('click', function(e) {
    var attribute = e.target.getAttribute('data-click');
    var target = e.target.getAttribute('data-target');
    var parts = attribute.split('-', 2);
    switch (parts[0]) {
      case 'toggle':
        if (target) {
          var targetEl = document.getElementById(target);
          if (targetEl) {
            var display = targetEl.style.display || 'none';
            targetEl.style.display = (display === 'none') ? 'block' : 'none';
          } else {
            console.warn('could not find target for close by given id ', target);
          }
        } else {
          var display = e.target.nextElementSibling.style.display || 'none';
          e.target.nextElementSibling.style.display = (display === 'none') ? 'block' : 'none';
        }
        break;
      case 'close':
        if (target) {
          var targetEl = document.getElementById(target);
          if (targetEl) {
            targetEl.style.display = 'none';
          } else {
            console.warn('could not find target for close by given id ', target);
          }
        } else {
          e.target.nextElementSibling.style.display = 'none';
        }
        break;
      case 'open':
        e.target.nextElementSibling.style.display = 'block';
        break;
      case 'play':
        if (target) {
          var video = document.getElementById(target);
          var id = target.split('-', 2)[1];
          if (video) {
            var thumb = document.getElementById('videothumb-' + id);
            var text = document.getElementById('text-' + id);
            var fullscreen = document.getElementById('fullscreen-' +  id);
            if (thumb) {
              thumb.style.display = 'none';
            }
            if (text) {
              text.style.display = 'none';
            }
            if (fullscreen) {
              fullscreen.style.display = 'block';
            }
            if (video.style.display === 'none') {
              video.style.display = 'block';
            }
            e.target.style.display = 'none';
            video.play();
            stopAutoplay = setInterval(function() {
              jQuery('.rslides').trigger('mouseenter');
            }, 500);
            setTimeout(function() {
                jQuery('.slideshow').on('click', function(event) {
                  jQuery('.slideshow .play .button').css('display', 'block');
                  fullscreen.style.display = 'none';
                  video.pause();
                  jQuery('.rslides').trigger('mouseleave');
                  clearInterval(stopAutoplay);
                  jQuery('.slideshow').unbind('click');
                });
              },
              200
            );

          } else {
            console.warn('could not find target for close by given id ', target);
          }
        }
        break;
      default:
        break;
    }
  }, false);
}
