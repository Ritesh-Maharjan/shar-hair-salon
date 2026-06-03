
const swiper = new Swiper('.swiper', {
	loop: true,
	autoHeight: true,
	navigation: {
			nextEl: '.swiper-button-next',
			prevEl: '.swiper-button-prev',
	},
	slidesPerView: 1,
	spaceBetween: 10,
	breakpoints: {
			800: {
					slidesPerView: 2,
					spaceBetween: 10
			},
	}
});

const swiperHome = new Swiper('.swiper-home', {
	loop: true,
	autoHeight: true,
	navigation: {
			nextEl: '.swiper-button-next',
			prevEl: '.swiper-button-prev',
	},
	slidesPerView: 1,
	spaceBetween: 10,
	breakpoints: {
			800: {
					slidesPerView: 3,
					spaceBetween: 20
			},
	}
});
