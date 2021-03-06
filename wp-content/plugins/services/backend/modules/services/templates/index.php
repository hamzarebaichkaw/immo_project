<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div id="bookly-tbs" class="wrap">
    <div class="bookly-tbs-body">
        <div class="page-header text-right clearfix">
            <div class="bookly-page-title">
                <?php _e( 'Services', 'bookly' ) ?>
            </div>
        </div>
        <div class="row">
            <div id="bookly-sidebar" class="col-sm-4">
                <div id="bookly-categories-list" class="bookly-nav">
                    <div class="bookly-nav-item active bookly-category-item bookly-js-all-services">
                        <div class="bookly-padding-vertical-xs"><?php _e( 'tous les  Services', 'bookly' ) ?></div>
                    </div>
                     <ul id="bookly-category-item-list">
                        <?php foreach ( $category_collection as $category ) include '_category_item.php'; ?>
                    </ul>
                </div>

                <div class="form-group">
                    <button id="bookly-new-category" type="button"
                            class="btn btn-xlg btn-block btn-success-outline">
                        <i class="dashicons dashicons-plus-alt"></i>
                        <?php _e( 'Nouveau Categorie', 'bookly' ) ?>
                    </button>
                </div>

                <form method="post" id="new-category-form" style="display: none">
                    <div class="form-group bookly-margin-bottom-md">
                        <div class="form-field form-required">
                            <label for="bookly-category-name"><?php _e( 'Nom', 'bookly' ) ?></label>
                            <input class="form-control" id="bookly-category-name" type="text" name="name" />
                            <input type="hidden" name="action" value="ab_category_form" />
                        </div>
                    </div>

                    <hr />
                    <div class="text-right">
                        <button type="submit" class="btn btn-success">
                            <?php _e( 'Sauvgarder', 'bookly' ) ?>
                        </button>
                        <button type="button" class="btn btn-default">
                            <?php _e( 'Annuler', 'bookly' ) ?>
                        </button>
                    </div>
                </form>
            </div>

            <div id="bookly-services-wrapper" class="col-sm-8">
                <div class="panel panel-default bookly-main">
                    <div class="panel-body">
                        <h4 class="bookly-block-head">
                            <span class="bookly-category-title"><?php _e( 'tous Services', 'bookly' ) ?></span>
                            <button type="button" class="add-service ladda-button pull-right btn btn-lg btn-success" data-spinner-size="40" data-style="zoom-in">
                                <span class="ladda-label"><i class="glyphicon glyphicon-plus"></i> <?php _e( 'Ajouter Service', 'bookly' ) ?></span>
                            </button>
                        </h4>

                        <p class="bookly-margin-top-xlg no-result" <?php if ( ! empty ( $service_collection ) ) : ?>style="display: none;"<?php endif ?>>
                            <?php _e( 'S `il vous plais ajouter  un service.', 'bookly' ) ?>
                        </p>

                        <div class="bookly-margin-top-xlg" id="ab-services-list">
                            <?php include '_list.php' ?>
                        </div>
                        <div class="text-right">
                            <?php \BooklyLite\Lib\Utils\Common::deleteButton() ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="ab-staff-update" class="modal fade" tabindex=-1 role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <div class="modal-title h2"><?php _e( 'Mettre à jour le paramètre de service', 'bookly' ) ?></div>
                </div>
                <div class="modal-body">
                    <p><?php _e( 'Vous allez modifier un paramètre de service qui est également configuré séparément pour chaque membre du personnel. Voulez-vous la mettre à jour dans les paramètres du personnel aussi?', 'bookly' ) ?></p>
                    <div class="checkbox">
                        <label>
                            <input id="ab-remember-my-choice" type="checkbox">
                            <?php _e( 'Rappelez-vous mon choix', 'bookly' ) ?>
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-default ab-no" data-dismiss="modal" aria-hidden="true">
                        <?php _e( 'Non, mise à jour juste ici dans les services', 'bookly' ) ?>
                    </button>
                    <button type="submit" class="btn btn-success ab-yes"><?php _e( 'oui', 'bookly' ) ?></button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="hidden">
    <?php do_action( 'bookly_render_after_service_list', $service_collection ) ?>
</div>