declare global {
  interface Window {
    gtag?: (...args: any[]) => void;
  }
}

/**
 * Send privacy-safe GA4 conversion/engagement events without PII.
 */
export const trackEvent = (
  eventName:
    | 'sign_up'
    | 'login'
    | 'view_pricing'
    | 'click_cta'
    | 'start_trial'
    | 'create_invoice'
    | 'subscribe',
  params: Record<string, string | number | boolean> = {}
): void => {
  if (typeof window === 'undefined' || typeof window.gtag !== 'function') {
    return;
  }

  window.gtag('event', eventName, params);
};
