/* =============================================
   GENÇ CAFE - Premium JavaScript
   ============================================= */

document.addEventListener('DOMContentLoaded', () => {

  // ---- Page Load Transition ---- //
  const body = document.body;
  body.classList.remove('page-loading');
  body.classList.add('page-loaded');

  // ---- Smooth Page Transition on Link Click ---- //
  const overlay = document.querySelector('.page-transition-overlay');
  const internalLinks = document.querySelectorAll('a[href]');

  internalLinks.forEach(link => {
    const href = link.getAttribute('href');
    // Only apply to internal PHP links — not external, anchor, or new-tab links
    const isInternal =
      href &&
      !href.startsWith('#') &&
      !href.startsWith('http') &&
      !href.startsWith('javascript') &&
      !link.hasAttribute('target') &&
      (href.endsWith('.php') || href.includes('.php?'));

    if (isInternal) {
      link.addEventListener('click', (e) => {
        e.preventDefault();
        const destination = href;
        if (overlay) {
          overlay.classList.add('exit-active');
          setTimeout(() => {
            window.location.href = destination;
          }, 480);
        } else {
          window.location.href = destination;
        }
      });
    }
  });

  // ---- Navbar Scroll Effect ---- //
  const navbar = document.querySelector('.navbar-genccafe');
  if (navbar) {
    const onScroll = () => {
      if (window.scrollY > 80) {
        navbar.classList.add('scrolled');
      } else {
        navbar.classList.remove('scrolled');
      }
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }

  // ---- Parallax Background ---- //
  const parallaxElements = document.querySelectorAll('[data-parallax]');
  if (parallaxElements.length > 0) {
    let ticking = false;

    const updateParallax = () => {
      const scrollY = window.pageYOffset;
      parallaxElements.forEach(el => {
        const speed   = parseFloat(el.dataset.parallax) || 0.3;
        const parent  = el.parentElement;
        const rect    = parent.getBoundingClientRect();
        const inView  = rect.bottom > 0 && rect.top < window.innerHeight;
        if (inView) {
          // Offset relative to the section's own scroll position
          const sectionOffset = parent.offsetTop;
          const relativeScroll = scrollY - sectionOffset + window.innerHeight / 2;
          const yPos = -(relativeScroll * speed);
          el.style.transform = `translate3d(0, ${yPos}px, 0)`;
        }
      });
      ticking = false;
    };

    window.addEventListener('scroll', () => {
      if (!ticking) {
        requestAnimationFrame(updateParallax);
        ticking = true;
      }
    }, { passive: true });

    updateParallax();
  }

  // ---- Scroll Reveal Animation ---- //
  const revealElements = document.querySelectorAll('.reveal, .reveal-scale, .reveal-left, .reveal-right');

  if (revealElements.length > 0) {
    const revealObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('revealed');
          revealObserver.unobserve(entry.target);
        }
      });
    }, {
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px'
    });

    revealElements.forEach(el => revealObserver.observe(el));
  }

  // ---- Admin Panel: Image Preview ---- //
  const imageInput = document.getElementById('itemImage');
  const imageEditInput = document.getElementById('editItemImage');

  if (imageInput) {
    imageInput.addEventListener('change', function () {
      previewImage(this, 'imagePreview');
    });
  }

  if (imageEditInput) {
    imageEditInput.addEventListener('change', function () {
      previewImage(this, 'editImagePreview');
    });
  }

  function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    if (input.files && input.files[0] && preview) {
      const reader = new FileReader();
      reader.onload = function (e) {
        preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
        preview.style.display = 'block';
      };
      reader.readAsDataURL(input.files[0]);
    }
  }

  // ---- Admin Panel: Edit Modal Population ---- //
  const editButtons = document.querySelectorAll('[data-edit-id]');
  editButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      const id = btn.dataset.editId;
      const name = btn.dataset.editName;
      const category = btn.dataset.editCategory;
      const price = btn.dataset.editPrice;
      const description = btn.dataset.editDescription;
      const image = btn.dataset.editImage;

      document.getElementById('editItemId').value = id;
      document.getElementById('editItemName').value = name;
      document.getElementById('editItemCategory').value = category;
      document.getElementById('editItemPrice').value = price;
      document.getElementById('editItemDescription').value = description;

      const currentImg = document.getElementById('editCurrentImage');
      if (currentImg) {
        if (image) {
          currentImg.innerHTML = `<img src="assets/uploads/${image}" alt="Current" style="max-width:150px;border-radius:12px;border:1px solid rgba(212,168,83,0.2);">`;
          currentImg.style.display = 'block';
        } else {
          currentImg.innerHTML = '';
          currentImg.style.display = 'none';
        }
      }

      // Reset new image preview
      const editPreview = document.getElementById('editImagePreview');
      if (editPreview) {
        editPreview.innerHTML = '';
        editPreview.style.display = 'none';
      }
    });
  });

  // ---- Admin Panel: Delete Confirmation ---- //
  const deleteButtons = document.querySelectorAll('[data-delete-id]');
  deleteButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      const id = btn.dataset.deleteId;
      const name = btn.dataset.deleteName;
      document.getElementById('deleteItemId').value = id;
      document.getElementById('deleteItemName').textContent = name;
    });
  });

});
