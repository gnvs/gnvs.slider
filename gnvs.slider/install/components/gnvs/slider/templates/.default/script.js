/**
 * Класс слайдера. Добавляет элементам обработчики событий, управляющие переключением слайдов.
 * Создания экземпляра класса достаточно для полноценной работы. Первым аргументом принимает id элемента слайдера
 * и объект опций 
 * Пример:
 * new Slider('sliderId', {
 * 	start: 1. 
 * 	showDots: false,
 * 	showArrors: false,
 * 	autoscroll: 0 
 * });
 *
 * Для удаления обработчиков событий нужно вызвать метод removeListeners();
 *
 * Slider class. Adds event handlers for elements which manages slide switching. First argument is an ID of target slider element
 * Creating a Slider instance is enough for work.
 * And object options 
 * Example:
 * new Slider('sliderId', {
 * 	start: 1. 
 * 	showDots: false,
 * 	showArrors: false,
 * 	autoscroll: 0 
 * });
 *
 * For removing event listeners from elements call method removeListeners();
 */
class Slider {
    /**
     * Конструктор слайдера. Создает необходимые для внутренних вычислений переменные и добавляет элементам
     * обработчики событий, связывая обработчик с текущим экземпляром класса. Принимает идентификатор слайдера
 	 * и массив опций 
 	 * start (int) - с какого слайда начнётся показ, по умолчанию 1. 
 	 * showDots (bool) показывать точки управления, по умолчанию false,
 	 * showArrors (bool) показывать стрелки управления, по умолчанию false,
 	 * autoscroll (int) интервал автосмены слайда, по умолчанию отчключен,
     * выбран после создания экземпляра
     * Class constructor. Creates variables required for calculating and adds event listeners by binding
     * listeners with class instance. Takes a slide index in arguments. Slide element with given index will be set
     * as chosen after creating an instance.
     * @param sliderId
     * @param options
     */
    constructor(sliderId, options = {}) {

		this.currentSlideIndex = 1;
		if ('start' in options) {
        	this.currentSlideIndex = options.start;
        }
        
        this.targetSliderElement = document.getElementById(sliderId);
        this.sliderTrack = this.targetSliderElement.querySelector('.slider-track');
        this.slides = this.targetSliderElement.getElementsByClassName("item");
        
        this.dots = false;
        if ('showDots' in options && options.showDots) {
        	this.dots = this.targetSliderElement.getElementsByClassName("slider-dots_item");
        }
        this.prev = false;
    	this.next = false;
		if ('showArrows' in options && options.showArrows) {
        	this.prev = [...this.targetSliderElement.getElementsByClassName('prev')].shift();
        	this.next = [...this.targetSliderElement.getElementsByClassName('next')].shift();
        }       

		this.interval = 0;
		if ('autoscroll' in options && options.autoscroll > 0) {
            this.interval = options.autoscroll;
        }
        
        this.slideWidth = this.slides[0].offsetWidth;
        this.transition = true;
        this.posInit = 0;
        this.posX1 = 0;
        this.posX2 = 0;

		if (!!this.dots) {
	        for (let element of this.dots) {
	            element.addEventListener('click', this.currentSlide.bind(this));
	        }
        }

		if (!!this.prev && !!this.next) {
	    	this.prev.addEventListener('click', this.minusSlide.bind(this));
        	this.next.addEventListener('click', this.plusSlide.bind(this));
        }
        

        this.targetSliderElement.addEventListener('touchstart', this.swipeStart.bind(this));

        if (this.interval > 0) {
            this.autoscroll(this.interval * 1000);
        }

        this.showSlides(this.currentSlideIndex);
        
        if ("IntersectionObserver" in window) {
			this.lazyload();
		}
    }

    /**
     * Метод переключения слайда на следующий элемент.
     * Method for switch slide to next element.
     */
    plusSlide() {
        this.showSlides(this.currentSlideIndex + 1)
    }

    /**
     * Метод переключения слайда на предыдущий элемент.
     * Method for switch slide to previous element.
     */
    minusSlide() {
        this.showSlides(this.currentSlideIndex - 1)
    }

    /**
     * Метод переключения слайда на текущий элемент. Номер элемента получается из event.target.dataset по ключу "slider-counter".
     * Method for switch slide to target element. Takes element index from event target.dataset by key "slider-counter".
     * @param event
     */
    currentSlide(event) {
        this.currentSlideIndex = Number.parseInt(event.target.dataset.sliderCounter);
        this.showSlides(this.currentSlideIndex);
    }

    /**
     * Основной метод управления слайдами. Принимает необходимый индекс и переключает слайд на элемент с этим индексом.
     * Base method for managing slides. Requires slide index in arguments and switches slides to element with given index.
     * @param slideIndex
     */
    showSlides(slideIndex) {
        if (this.transition) {
            this.sliderTrack.style.transition = "transform .5s";
        }
        this.currentSlideIndex = slideIndex
        if (slideIndex > this.slides.length) {
            this.currentSlideIndex = 1;
        }

        if (slideIndex < 1) {
            this.currentSlideIndex = this.slides.length
        }

		if (!!this.dots) {
	        for (let index = 0; index < this.slides.length; index++) {
	            if ((index) === this.currentSlideIndex - 1) {
	                this.dots[index].className += " active"
	                continue;
	            }
	            this.dots[index].className = this.dots[index].className.replace(' active', '')
	        }
    	}
    	
        this.sliderTrack.style.transform = `translate3d(-${(this.currentSlideIndex - 1) * this.slideWidth}px, 0px, 0px)`;
    }

     /**
     * Добавляет автоскролл к слайдеру. По умолчанию 4 секунды
     * Add autoscroll to slider. Default value is 4 seconds
     * @param interval
     */
    autoscroll(interval = 4000) {
        if (interval == 0) {
            return;
        }
        setInterval(
            () => { this.showSlides(this.currentSlideIndex + 1); },
            interval
        )
    }

    moveSlide(event) {
        this.posX2 = this.posX1 - event.touches[0].clientX;
        this.posX1 = event.touches[0].clientX;
    }

    swipeStart(event){
        this.posInit = this.posX1 = event.touches[0].clientX;
        this.targetSliderElement.addEventListener('touchmove', BX.proxy(this.moveSlide, this));
        this.targetSliderElement.addEventListener('touchend', BX.proxy(this.swipeEnd, this));
    }

    swipeEnd(){
        this.targetSliderElement.removeEventListener('touchmove', BX.proxy(this.moveSlide, this));
        this.targetSliderElement.removeEventListener('touchend', BX.proxy(this.swipeEnd, this));

        if (this.posX1 > this.posInit){
            this.showSlides(this.currentSlideIndex - 1);
            return;
        }

        if (this.posX1 < this.posInit) {
            this.showSlides(this.currentSlideIndex + 1);
        }
    }
    
	lazyload(){
		let options = {
		  	slider: this,
			threshold: 0.75
		};
		let observeSlider = new IntersectionObserver(function(entries) {
			entries.forEach(function(entry) {
		        if (entry.isIntersecting) {
					options.slider.loadImages();
					observeSlider.unobserve(entry.target);
		        }
	        });
	    }, options);
		observeSlider.observe(this.targetSliderElement);
	}
	
	loadImages(){
		let images = this.targetSliderElement.getElementsByTagName('img');
		let options = {
		  	root: this.targetSliderElement,
		};

		let observeImage = new IntersectionObserver(function(entries) {
	      	entries.forEach(function(entry) {
		        if (entry.isIntersecting) {
					var image = entry.target;
					image.src = image.dataset.src;
					observeImage.unobserve(image);
		        }
	      	});
	    }, options);
	    
	    Array.prototype.forEach.call(images, function (image) {
			observeImage.observe(image);
	    });
	}
}
