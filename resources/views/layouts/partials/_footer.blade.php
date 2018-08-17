<footer class="footer">
	<div class="container">
		<div class="row">
			<div class="col-xs-6 col-md-4 col-lg-4">
				<h4 class="footer__heading">StackrApp</h4>
				<ul class="list-unstyled footer__list">
					<li class="footer__list-item">
                        <a class="footer__link {{ Request::is('about') ? 'active' : '' }}" href="{{ route('about') }}" title="link to about page">About</a>
                   	</li>
					<li class="footer__list-item">
                        <a class="footer__link {{ Request::is('pricing') ? 'active' : '' }}" href="{{ route('pricing') }}" title="link to pricing page">Pricing</a>
                   	</li>
					<li class="footer__list-item">
                        <a class="footer__link {{ Request::is('features') ? 'active' : '' }}" href="{{ route('features') }}" title="link to features page">Features</a>
                   	</li>
				</ul>
			</div>
			<div class="col-xs-6 col-md-4 col-lg-4">
				<h4 class="footer__heading">Support</h4>
				<ul class="list-unstyled footer__list">
					<li class="footer__list-item">
						<a class="footer__link" href="mailto:damien@stackrapp.com?subject=StackrApp" title="link Damien's email">Email us</a>
					</li>
					<li class="footer__list-item">
						<a class="footer__link {{ Request::is('contact') ? 'active' : '' }}" href="http://stackrapp.helpsite.io/" target="_blank" title="link to documentation">Help</a>
					</li>
				</ul>
			</div>
			<div class="col-xs-12 col-md-4 col-lg-4">
				<h4 class="footer__heading">@Copyright {{ Carbon\Carbon::now()->format('Y') }}</h4>
				<ul class="list-unstyled footer__list">
					<li class="footer__list-item">
						<a class="footer__link" target="_blank" href="https://www.google.com/maps/place/Worcester,+MA" title="Link to Google Maps Worcester MA">Built with &#9829; in Massachussets</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</footer>