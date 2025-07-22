<script lang="ts">
	import { Button } from '@/components/ui/button';
	import { Input } from '@/components/ui/input';
	import { authStore } from '$lib/stores/auth';

	const heading = 'Login';
	const logo = {
		url: 'https://www.shadcnblocks.com',
		src: 'https://deifkwefumgah.cloudfront.net/shadcnblocks/block/logos/shadcnblockscom-wordmark.svg',
		alt: 'logo',
		title: 'shadcnblocks.com'
	};
	const buttonText = 'Login';
	const signupText = 'Not a user yet?';
	const signupUrl = '/signup';
</script>

<section class="h-screen bg-muted">
	<div class="flex h-full items-center justify-center">
		<div class="flex flex-col items-center gap-6 lg:justify-start">
			<a href={logo.url}>
				<img src={logo.src} alt={logo.alt} title={logo.title} class="h-10 dark:invert" />
			</a>
			<div
				class="flex w-full max-w-sm min-w-sm flex-col items-center gap-y-4 rounded-md border border-muted bg-background px-6 py-8 shadow-md"
			>
				<h1 class="text-xl font-semibold">{heading}</h1>

				<!-- Error message display -->
				{#if $authStore.error}
					<div
						class="w-full rounded-md border border-red-200 bg-red-50 p-3 text-sm text-red-700"
					>
						{$authStore.error}
					</div>
				{/if}

				<form action="?/login" class="flex w-full flex-col gap-y-4" method="post">
					<Input
						id="email"
						type="email"
						name="email"
						placeholder="Email"
						tabindex={1}
						class="text-sm"
						required
						disabled={$authStore.loading}
					/>
					<Input
						id="password"
						type="password"
						name="password"
						placeholder="Password"
						class="text-sm"
						tabindex={2}
						required
						disabled={$authStore.loading}
					/>
					<Button type="submit" class="w-full" disabled={$authStore.loading}>
						{$authStore.loading ? 'Logging in...' : buttonText}
					</Button>
				</form>
			</div>
			<div class="flex justify-center gap-1 text-sm text-muted-foreground">
				<p>{signupText}</p>
				<a href={signupUrl} class="font-medium text-primary hover:underline"> Sign up </a>
			</div>
		</div>
	</div>
</section>
