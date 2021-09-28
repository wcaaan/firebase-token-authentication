<?php
	namespace Wcaaan\FirebaseTokenAuthentication;
	
	use Illuminate\Http\Request;
	use Firebase\Auth\Token\Verifier;
	
	class FirebaseTokenAuthenticationGuard
	{
		/**
		 * @var Firebase\Auth\Token\Verifier
		 */
		protected $verifier;
		
		/**
		 * Constructor.
		 *
		 * @param Verifier $verifier
		 *
		 * @return void
		 */
		public function __construct(Verifier $verifier)
		{
			$this->verifier = $verifier;
		}
		
		/**
		 * Get User by request claims.
		 *
		 * @param Request $request
		 *
		 * @return mixed|null
		 */
		public function user(Request $request)
		{
			$token = $request->bearerToken();
			if (empty($token))
			{
				return;
			}
			
			try
			{
				$firebaseToken = $this->verifier->verifyIdToken($token);
				$targetProvider = config('firebase-token-authentication.target_provider');
				
				return app(config('auth.providers.'.$targetProvider.'.model'))
					->resolveByClaims($firebaseToken)
					->setFirebaseAuthenticationToken($token);
			}
			catch (\Exception $exception)
			{
				if ($exception instanceof \Firebase\Auth\Token\Exception\ExpiredToken)
				{
					return;
				}
				
				if (config('app.debug'))
				{
					throw $exception;
				}
				
				return;
			}
		}
	}