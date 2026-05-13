<?php
/**
 * CHIM GTS Bridge function registration.
 * Papyrus receives commands through CHIM_CommandReceived and CHIMGTSPluginBridge.psc.
 */

$gtsActionsFile = __DIR__ . "/metadata/actions.json";
$gtsActions = [];
$gtsActionDescriptions = [];

if (file_exists($gtsActionsFile)) {
    $decoded = json_decode(file_get_contents($gtsActionsFile), true);
    if (is_array($decoded)) {
        foreach ($decoded as $entry) {
            if (isset($entry["key"])) {
                $gtsActions[] = $entry["key"];
                $gtsActionDescriptions[] = $entry["key"] . ": " . ($entry["description"] ?? "GTS action");
            }
        }
    }
}

if (count($gtsActions) === 0) {
    $gtsActions = ["gts_grab", "gts_release", "gts_grow_self", "gts_shrink_self", "gts_stomp"];
}

$gtsActionSummary = implode("; ", array_slice($gtsActionDescriptions, 0, 80));

$GLOBALS["F_NAMES"]["ExtCmdGTSAction"] = "GTSAction";
$GLOBALS["F_TRANSLATIONS"]["ExtCmdGTSAction"] =
    "Perform a GTS / Size Matters NG action. Use only when physically plausible and consistent with the NPC's personality. " .
    "The single request parameter must be exactly: action_key|target_name|amount. " .
    "Targets may be Player, self, crosshair, or an exact visible/nearby actor display name. " .
    "Known actions include: " . implode(", ", $gtsActions) . ".";

$GLOBALS["FUNCTIONS"][] = [
    "name" => $GLOBALS["F_NAMES"]["ExtCmdGTSAction"],
    "description" => $GLOBALS["F_TRANSLATIONS"]["ExtCmdGTSAction"],
    "parameters" => [
        "type" => "object",
        "properties" => [
            "request" => [
                "type" => "string",
                "description" => "Pipe-separated request: action_key|target_name|amount. Examples: gts_grab|Player|0.25, gts_grow_self|self|0.50, gts_stomp|crosshair|0.25. Valid action keys include: " . implode(", ", $gtsActions)
            ]
        ],
        "required" => ["request"]
    ]
];

$GLOBALS["ENABLED_FUNCTIONS"][] = "ExtCmdGTSAction";

$GLOBALS["PROMPTS"]["afterfunc"]["cue"]["ExtCmdGTSAction"] =
    "{$GLOBALS["HERIKA_NAME"]} naturally reacts to the outcome of the GTS action, without mentioning internal command syntax. {$GLOBALS["TEMPLATE_DIALOG"]}";

$GLOBALS["FUNCRET"]["ExtCmdGTSAction"] = function($gameRequest) {
    $GLOBALS["FORCE_MAX_TOKENS"] = 80;
    if (isset($gameRequest[3]) && stripos($gameRequest[3], "error") !== false) {
        return [
            "argName" => "request",
            "request" => "{$GLOBALS["HERIKA_NAME"]} cannot complete that GTS action and reacts naturally. {$GLOBALS["TEMPLATE_DIALOG"]}"
        ];
    }
    return ["argName" => "request"];
};
?>
