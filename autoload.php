<?php
function ChiaraAutoload($class)
{
    if (strpos($class, 'Chiara\\') === 0) {
        include __DIR__ . '/' . str_replace('\\', '/', $class) . '.php';
    }
}

$GLOBALS['___PODIO_MAP'] =
array(
  'Podio' => 'lib/Podio.php',
  'PodioResponse' => 'lib/PodioResponse.php',
  'PodioOAuth' => 'lib/PodioOAuth.php',
  'PodioError' => 'lib/PodioError.php',
  'PodioObject' => 'lib/PodioObject.php',
  'PodioLogger' => 'lib/PodioLogger.php',
  'PodioSession' => 'lib/PodioSession.php',
  
  'PodioSuperApp' => 'models/PodioSuperApp.php',
  'PodioAction' => 'models/PodioAction.php',
  'PodioActivity' => 'models/PodioActivity.php',
  'PodioApp' => 'models/PodioApp.php',
  'PodioAppField' => 'models/PodioAppField.php',
  'PodioAppMarketShare' => 'models/PodioAppMarketShare.php',
  'PodioBatch' => 'models/PodioBatch.php',
  'PodioByLine' => 'models/PodioByLine.php',
  'PodioCalendarEvent' => 'models/PodioCalendarEvent.php',
  'PodioCalendarMute' => 'models/PodioCalendarMute.php',
  'PodioComment' => 'models/PodioComment.php',
  'PodioContact' => 'models/PodioContact.php',
  'PodioConversation' => 'models/PodioConversation.php',
  'PodioConversationMessage' => 'models/PodioConversationMessage.php',
  'PodioConversationParticipant' => 'models/PodioConversationParticipant.php',
  'PodioEmbed' => 'models/PodioEmbed.php',
  'PodioFile' => 'models/PodioFile.php',
  'PodioForm' => 'models/PodioForm.php',
  'PodioGrant' => 'models/PodioGrant.php',
  'PodioHook' => 'models/PodioHook.php',
  'PodioImporter' => 'models/PodioImporter.php',
  'PodioIntegration' => 'models/PodioIntegration.php',
  'PodioItem' => 'models/PodioItem.php',
  'PodioItemDiff' => 'models/PodioItemDiff.php',
  'PodioItemField' => 'models/PodioItemField.php',
  'PodioItemRevision' => 'models/PodioItemRevision.php',
  'PodioLinkedAccountData' => 'models/PodioLinkedAccountData.php',
  'PodioNotification' => 'models/PodioNotification.php',
  'PodioNotificationContext' => 'models/PodioNotificationContext.php',
  'PodioNotificationGroup' => 'models/PodioNotificationGroup.php',
  'PodioOrganization' => 'models/PodioOrganization.php',
  'PodioOrganizationMember' => 'models/PodioOrganizationMember.php',
  'PodioQuestion' => 'models/PodioQuestion.php',
  'PodioQuestionAnswer' => 'models/PodioQuestionAnswer.php',
  'PodioQuestionOption' => 'models/PodioQuestionOption.php',
  'PodioRating' => 'models/PodioRating.php',
  'PodioRecurrence' => 'models/PodioRecurrence.php',
  'PodioReference' => 'models/PodioReference.php',
  'PodioReminder' => 'models/PodioReminder.php',
  'PodioSearchResult' => 'models/PodioSearchResult.php',
  'PodioSpace' => 'models/PodioSpace.php',
  'PodioSpaceMember' => 'models/PodioSpaceMember.php',
  'PodioStatus' => 'models/PodioStatus.php',
  'PodioStreamObject' => 'models/PodioStreamObject.php',
  'PodioSubscription' => 'models/PodioSubscription.php',
  'PodioTag' => 'models/PodioTag.php',
  'PodioTagSearch' => 'models/PodioTagSearch.php',
  'PodioTask' => 'models/PodioTask.php',
  'PodioTaskLabel' => 'models/PodioTaskLabel.php',
  'PodioUser' => 'models/PodioUser.php',
  'PodioUserMail' => 'models/PodioUserMail.php',
  'PodioUserStatus' => 'models/PodioUserStatus.php',
  'PodioVia' => 'models/PodioVia.php',
  'PodioView' => 'models/PodioView.php',
  'PodioWidget' => 'models/PodioWidget.php',
);
function Podio_autoload($class)
{
    if (0 !== strpos($class, 'Podio')) return;
    if (!isset($GLOBALS['___PODIO_MAP'][$class])) {
        if (false !== strpos($class, 'ItemField')) {
            $class = 'PodioItemField';
        } elseif (false !== strpos($class, 'Error')) {
            $class = 'PodioError';
        } else {
            return false;
        }
    }
    include __DIR__ . '/oldpodio/' . $GLOBALS['___PODIO_MAP'][$class];
}
spl_autoload_register('ChiaraAutoload');
spl_autoload_register('Podio_autoload');