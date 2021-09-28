<?php
	namespace Wcaaan\FirebaseTokenAuthentication;
	
	use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
	use Firebase\Auth\Token\Verifier;
	use Auth;
	
	class FirebaseTokenAuthenticationServiceProvider extends ServiceProvider
	{
		/**
		 * Bootstrap the application services.
		 */
		public function boot()
		{
			$this->registerPolicies();
			
			if ($this->app->runningInConsole())
			{
				$this->publishes([
					__DIR__ . '/../config/config.php' => config_path('firebase-token-authentication.php'),
				], 'config');
				
			}
			
			Auth::viaRequest('firebase', function ($request)
			{
				return app(FirebaseTokenAuthenticationGuard::class)->user($request);
			});
		}
		
		/**
		 * Register the application services.
		 */
		public function register()
		{
			$this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'firebase-token-authentication');
			
			$this->app->singleton(Verifier::class, function ($app)
			{
				$firebaseProject = config('firebase-token-authentication.firebase_project_id');
				if (empty($firebaseProject))
				{
					throw new \Exception('firebase_project_id is missing in configuration.');
				}
				
				return new Verifier($firebaseProject);
			});
		}
	}
